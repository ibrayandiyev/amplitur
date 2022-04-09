<?php

use App\Enums\PersonType;
use App\Models\City;
use App\Models\Currency;
use App\Repositories\CategoryRepository;
use App\Repositories\CountryRepository;
use App\Repositories\CurrencyQuotationRepository;
use App\Repositories\CurrencyRepository;
use App\Repositories\StateRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('ip')) {
    /**
     * Get remote ip address considering cloudflare
     *
     * @return  string
     */
    function ip()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP'])) {
            return $_SERVER['HTTP_CF_CONNECTING_IP'];
        }

        return $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
    }
}

if (!function_exists('user')) {
    /**
     * Get request user
     *
     * @return  User|Provider|null
     */
    function user()
    {
        return request()->user();
    }
}

if (!function_exists('forbidden')) {
    /**
     * Abort request with forbidden message
     *
     * @return  void
     */
    function forbidden()
    {
        abort(403, 'This action is unauthorized');
    }
}

if (!function_exists('bugtracker')) {
    /**
     * Get BugTracker instance
     *
     * @return  \App\Services\BugTracker\BugTracker
     */
    function bugtracker()
    {
        return app('bugtracker');
    }
}


if (!function_exists('category')) {

    /**
     * [category description]
     *
     * @param   [type]  $category  [$category description]
     *
     * @return  string            [return description]
     */
    function category($category): ?array
    {
        $entities = app(CategoryRepository::class)->getAsKeyValue();

        $entity = $entities[$category] ?? null;

        return $entity;
    }
}

if (!function_exists('country')) {
    /**
     * Get country based on iso2 or it's text
     *
     * @param   mixed  $country
     * 
     * @return  string
     */
    function country($country): string
    {
        if (!isset($country->iso2)) {
            $countries = app(CountryRepository::class)->getAsKeyValue();

            return $countries[$country][app()->getLocale()] ?? $country ?? '';
        }

        if (empty($country->iso2) || empty($country)) {
            return '';
        }

        $countries = app(CountryRepository::class)->getAsKeyValue();

        if (!$countries) {
            return '';
        }

        $country = $countries[$country->iso2][app()->getLocale()];

        unset($countries);

        return $country;
    }
}

if (!function_exists('countryDetails')) {

    /**
     * [state description]
     *
     * @param   [type]  $country  [$country description]
     *
     * @return  string            [return description]
     */
    function countryDetails($country): ?array
    {
        $countries = app(CountryRepository::class)->getAsKeyValue();

        $country = $countries[$country] ?? null;

        return $country;
    }
}

if (!function_exists('state')) {
    /**
     * Get state based on iso2 or it's text
     *
     * @param   mixed  $country
     * @param   mixed  $state
     * 
     * @return  string
     */
    function state($country, $state): string
    {
        if (!isset($state->iso2)) {
            $states = app(StateRepository::class)->getAsKeyValue();

            return $states[$country->iso2 ?? $country][$state->iso ?? $state] ?? $state ?? '';
        }

        if (empty($state->iso2) || empty($state)) {
            return '';
        }

        $states = app(StateRepository::class)->getAsKeyValue();

        if (!$states) {
            return '';
        }

        $state = $states[$country->iso2 ?? $country][$state->iso2];

        unset($states);

        return $state;
    }
}

if (!function_exists('city')) {
    /**
     * Get city based on iso2 or it's text
     *
     * @param   mixed  $city
     * 
     * @return  string
     */
    function city($city): string
    {
        if (isset($city->name)) {
            return $city->name;
        }

        if (is_numeric($city)) {
            
            $city = City::find($city);
        }

        return $city->name ?? $city ?? '';
    }
}

if (!function_exists('name')) {
    /**
     * Get entity name based on entity person type
     *
     * @param   mixed  $entity
     * 
     * @return  string
     */
    function name($entity): string
    {
        if ($entity->type == PersonType::FISICAL) {
            return $entity->name;
        } else {
            return $entity->company_name;
        }
    }
}

if (!function_exists('money')) {
    /**
     * [money description]
     *
     * @param   [type]  $value  [$value description]
     *
     * @return  [type]          [return description]
     */
    function money($value, $currency = null, $originCurrency = null)
    {
        if (!empty($originCurrency)) {
            $value = app(CurrencyQuotationRepository::class)->convert($value, $originCurrency, $currency);
        }

        if (!empty($currency) && is_string($currency)) {
            return $currency . ' ' . moneyDecimal($value);
        }

        if (!empty(currency())) {
            return currency()->code . " " . moneyDecimal($value);
        }

        return moneyDecimal($value);
    }
}

if (!function_exists('moneyFloat')) {
    function moneyFloat($value, $currency = null, $originCurrency = null)
    {
        if (!empty($originCurrency)) {
            $value = app(CurrencyQuotationRepository::class)->convert($value, $originCurrency, $currency);
        }

        return $value;
    }
}

if (!function_exists('moneyDecimal')) {
    function moneyDecimal($value, $decimal = 2)
    {
        if($value === null) return null;
        $value = str_replace(',', '', $value);
        return number_format($value, $decimal, ',', '.');
    }
}

if (!function_exists('decimal')) {
    function decimal($value, $decimal = 2)
    {
        $value = str_replace(',', '', $value);
        return number_format($value, $decimal, '.', '');
    }
}

if (!function_exists('parseNumber')) {
    function parseNumber($value, $decimal = 2)
    {
        $value = str_replace(',', '.', str_replace('.', '', $value));
        return number_format($value, $decimal, '.', '');
    }
}

if (!function_exists('percent')) {
    /**
     * [money description]
     *
     * @param   [type]  $value  [$value description]
     *
     * @return  [type]          [return description]
     */
    function percent($value, $decimals = 0)
    {
        $value = number_format($value * 100, $decimals, ',', '.');

        return $value . '%';
    }
}

if (!function_exists('sanitizeMoney')) {
    /**
     * [sanitizeMoney description]
     *
     * @param   [type]  $value  [$value description]
     *
     * @return  [type]          [return description]
     */
    function sanitizeMoney($value)
    {
        if (strpos($value, ',')) {
            return str_replace(',', '.', str_replace('.', '', $value));
        }
        if(!is_numeric($value)){
            $value = 0;
        }

        return (float) number_format($value, 2, ".", "");
    }
}

if (!function_exists('token')) {
    /**
     * [token description]
     *
     * @param   [type]$prefix  [$prefix description]
     * @param   null  $length  [$length description]
     *
     * @return  [type]         [return description]
     */
  function token($prefix = null, $length = 24)
  {
    if (empty($prefix)) {
        $prefix = Str::random(2);
    }

    if (!empty($length)) {
        $length = $length - 2;
    }

    $token = Str::random($length);
    $token = Str::upper($prefix . $token);

    return $token;
  }
}

if (!function_exists('formatDate')) {
    /**
     * [formatDate description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    function formatDate($date)
    {
        return $date->format('d/m/Y');
    }
}

if (!function_exists('tomorrow')) {
    /**
     * [tomorrow description]
     *
     * @param   [type]  $date  [$date description]
     *
     * @return  [type]         [return description]
     */
    function tomorrow($date)
    {
        $tomorrow = clone $date;

        return date_add($tomorrow, date_interval_create_from_date_string('1 day'));
    }
}

if (!function_exists('currency')) {
    /**
     * [currency description]
     *
     * @return  [type]  [return description]
     */
    function currency(?Currency $currency = null)
    {
        if(!empty($currency)) {
            session()->put('currency', $currency);
        }

        $currency = session()->get('currency');

        return $currency ?? app(CurrencyRepository::class)->findByCode('USD');
    }
}

if (!function_exists('language')) {
    /**
     * [language description]
     *
     * @return  [type]  [return description]
     */
    function language(?string $language = null): string
    {
        if(!empty($language)) {
            session()->put('language', $language);
        }

        $language = session()->get('language');

        return $language ?? 'en';
    }
}

if (!function_exists('getRouteByLanguage')) {
    /**
     * [language description]
     *
     * @return  [type]  [return description]
     */
    function getRouteByLanguage(?string $route = null): string
    {
        return language() .".". $route;
    }
}

if (!function_exists('getViewByLanguage')) {
    /**
     * [language description]
     *
     * @return  [type]  [return description]
     */
    function getViewByLanguage(?string $view = null, ?string $glue="."): string
    {
        return $view .$glue. language();
    }
}

if (!function_exists('translatedLanguage')) {
    /**
     * [language description]
     *
     * @return  [type]  [return description]
     */
    function translatedLanguage(?string $language = null)
    {
        if ($language == 'pt-br') {
            return __('messages.portuguese');
        }

        if ($language == 'en') {
            return __('messages.english');
        }

        if ($language == 'es') {
            return __('messages.spanish');
        }
    }
}

if (!function_exists('dateByLanguage')) {
    /**
     * [language description]
     *
     * @return  [type]  [return description]
     */
    function dateByLanguage(?string $language = null)
    {
        switch($language){
            case App\Enums\Language::ENGLISH:
            case App\Enums\Language::PORTUGUESE:
                return "d/m/Y";
                break;
            case App\Enums\Language::SPANISH:
                return "d/m/Y";
                break;
        }
        
    }
}

if (!function_exists('ddi')) {
    /**
     * [currency description]
     *
     * @return  [type]  [return description]
     */
    function ddi($country)
    {
        $country = countryDetails($country);

        return $country['phonecode'] ?? null;
    }
}

if (!function_exists('phone')) {
    /**
     * [currency description]
     *
     * @return  [type]  [return description]
     */
    function phone($phone, $ddi)
    {
        return str_replace($ddi, '', $phone);
    }
}

if (!function_exists('spread')) {
    /**
     * [spread description]
     *
     * @return  [type]  [return description]
     */
    function spread($value, $spread, $targetCurrency, $originCurrency)
    {
        $spreaded = decimal($value * $spread);
        $quotation = decimal(1);

        if (is_numeric($targetCurrency)) {
            $targetCurrency = Currency::find($targetCurrency);
        } else if (is_string($targetCurrency)) {
            $targetCurrency = Currency::whereCode($targetCurrency)->first();
        }

        if (is_numeric($originCurrency)) {
            $originCurrency = Currency::find($originCurrency);
        } else if (is_string($originCurrency)) {
            $originCurrency = Currency::whereCode($originCurrency)->first();
        }

        return "<span class=\"label label-light-inverse\">{$targetCurrency->code} {$quotation}</span><span class=\"label label-inverse\">{$originCurrency->code} {$spreaded}</span>";
    }
}

if (!function_exists('image')) {
    function image($path)
    {
        return Storage::disk('images')->url(imagePath() . $path);
    }
}

if (!function_exists('in_array_any')) {
    function in_array_any($needles, $haystack) {
        return ! empty(array_intersect($needles, $haystack));
    }
}



if (!function_exists('voucherFile')) {
    function voucherFile($path)
    {
        return Storage::disk('vouchers')->url(voucherPath() . $path);
    }
}

if (!function_exists('imagePath')) {
    function imagePath()
    {
        $bucketPath = env('AWS_IMAGE_PATH', '');

        if (!empty($bucketPath)) {
            $bucketPath .= '/';
        }

        return $bucketPath;
    }
}

if (!function_exists('voucherPath')) {
    function voucherPath()
    {
        $bucketPath = env('AWS_VOUCHER_PATH', '');

        if (!empty($bucketPath)) {
            $bucketPath .= '/';
        }

        return $bucketPath;
    }
}

if (!function_exists('lastWord')) {
    /**
     * [lastWord description]
     *
     * @param   string  $string  [$string description]
     *
     * @return  [type]           [return description]
     */
    function lastWord(string $string)
    {
        $words = explode(' ', $string);

        return $words[sizeof($words) - 1];
    }
}

if (!function_exists('convertDate')) {
    /**
     * [convertDate description]
     *
     * @param   string  $date  [$date description]
     *
     * @return  string
     */
    function convertDate(string $date, bool $reverse = false)
    {
        if (!$reverse) {
            $date = explode('/', $date);
            $date = "{$date[2]}-{$date[1]}-{$date[0]}";
        } else {
            $date = explode('-', $date);
            $date = "{$date[2]}/{$date[1]}/{$date[0]}";
        }

        return $date;
    }
}

if (!function_exists('convertDatetime')) {
    /**
     * [convertDatetime description]
     *
     * @param   string  $date  [$date description]
     *
     * @return  string
     */
    function convertDatetime(?string $date)
    {
        if(strstr($date, "_") !==false){
            $date = null;
        }
        if (empty($date)) {
            return null;
        }

        $date = Carbon::createFromFormat('d/m/Y, H:i', $date);
        $date = $date->format('Y-m-d H:i');

        return $date;
    }
}

if (!function_exists('checkPackageStartDateTime')) {
    /**
     * [convertDatetime description]
     *
     * @param   string  $date  [$date description]
     *
     * @return  string
     */
    function checkPackageStartDateTime(?string $startDate, ?string $endDate)
    {
        
        if($endDate == ""){
            $endDate = Carbon::createFromFormat('Y-m-d H:i', $startDate);
        }else{
            $endDate    = Carbon::createFromFormat('Y-m-d H:i', $endDate);
        }
        $startDate  = Carbon::createFromFormat('Y-m-d H:i', $startDate);
        $date       = $endDate->format('Y-m-d H:i');

        if($endDate->lessThanOrEqualTo($startDate)){
            // Rule: When an event is one day, we need to set "end_date" for the start_date
            $date   = $startDate->format("Y-m-d H:i");
        }

        return $date;
    }
}

if (!function_exists('moneyToString')) {
    function moneyToString($valor = null, $moeda = 'BRL', $lang = 'pt-br')
    {
        if ($moeda == 'BRL') {
            if ($lang == 'en') {
                $strmoeda = 'real';
                $strmoedap = 'reais';
            } else if ($lang == 'es') {
                $strmoeda = 'real';
                $strmoedap = 'reais';
            } else {
                $strmoeda = 'real';
                $strmoedap = 'reais';
            }
        } else if ($moeda == 'USD') {
            if ($lang == 'en') {
                $strmoeda = 'dollar';
                $strmoedap = 'dollars';
            } else if ($lang == 'es') {
                $strmoeda = 'dolar';
                $strmoedap = 'dolares';
            } else {
                $strmoeda = 'dólar';
                $strmoedap = 'dólares';
            }
        } else {
            $strmoeda = 'euro';
            $strmoedap = 'euros';
        }

        if ($lang == 'en') {
            $singular = array("cent", $strmoeda, "thousand", "million", "billion", "trillion", "quatrillion");
            $plural = array("cents", $strmoedap, "thousand", "million", "billion", "trillion", "quatrillion");
            $c = array("", "one hundred", "two hundred", "three hundred", "four hundred", "five hundred", "six hundred", "seven hundred", "eight hundred", "nine hundred");
            $d = array("", "ten", "twenty", "thirty", "forty", "fifty", "sixty", "seventy", "eighty", "ninety");
            $d10 = array("ten", "eleven", "twelve", "thirteen", "fourteen", "fifteen", "sixteen", "seventeen", "eighteen", "nineteen");
            $u = array("", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine");
            $c_100 = array("", "one hundred");
            $e = "and";
        } else if ($lang == 'es') {
            $singular = array("centavo", $strmoeda, "mil", "millón", "billón", "trillón", "quatrillón");
            $plural = array("centavos", $strmoedap, "mil", "millón", "billón", "trillón", "quatrillón");
            $c = array("", "cien", "doscientos", "trescientos", "quatrocientos", "quinientos", "seiscientos", "setecientos", "ochocientos", "novecientos");
            $d = array("", "diez", "veinte", "treinta", "quarenta", "cincuenta", "sessenta", "setenta", "ochenta", "noventa");
            $d10 = array("diez", "once", "doce", "trece", "catorce", "quince", "dieciséis", "diecisiete", "dieciocho", "diecinueve");
            $u = array("", "uno", "dos", "tres", "quatro", "cinco", "seis", "siete", "ocho", "nueve");
            $c_100 = array("", "ciento");
            $e = "y";
        } else {
            $singular = array("centavo", $strmoeda, "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", $strmoedap, "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
            $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
            $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
            $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
            $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
            $c_100 = array("", "cento");
            $e = "e";
        }

        $z = 0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);
        for ($i = 0; $i < count($inteiro); $i++) {
            for ($ii = strlen($inteiro[$i]); $ii < 3; $ii++) {
                $inteiro[$i] = "0" . $inteiro[$i];
            }
        }

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
        $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
        $rt = '';

        for ($i = 0; $i < count($inteiro); $i++) {
            $valor = $inteiro[$i];

            $rc = (($valor > 100) && ($valor < 200)) ? $c_100[1] : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc . (($rc && ($rd || $ru)) ? " {$e} " : "") . $rd . (($rd && $ru) ? " {$e} " : "") . $ru;
            $t = count($inteiro) - 1 - $i;
            $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
            if ($valor == "000") $z++;
            elseif ($z > 0) $z--;
            if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) $r .= (($z > 1) ? " de " : "") . $plural[$t];
            if ($r) $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " $e ") : " ") . $r;
            $rc = "";
        }

        $rt = strtoupper(trim($rt));
        return ($rt ? $rt : "zero");
    }
}