<?php

namespace App\Services\Imports;

use App\Enums\Currency;
use App\Enums\Language;
use App\Enums\PersonType;
use App\Enums\ProcessStatus;
use App\Models\City;
use App\Models\Country;
use Illuminate\Support\Facades\DB;

trait ImportLegacyTrait
{
    private $_countries;
    private $_countriesDdi;
    private $_countriesId;

    /**
     * @var BookingVoucherRepository
     */
    protected $repository;

    /**
     * @var BookingRepository
     */
    protected $bookingRepository;

    /**
     * @var BookingBillRepository
     */
    protected $bookingBillRepository;

    /**
     * @var BookingBillRefundRepository
     */
    protected $bookingBillRefundRepository;

    /**
     * @var BookingClientRepository
     */
    protected $bookingClientRepository;

    /**
     * @var BookingPassengerRepository
     */
    protected $bookingPassengerRepository;

    /**
     * @var BookingLogRepository
     */
    protected $bookingLogRepository;


    public function getGender($gender){
        //Gender
        switch($gender){
            case 1:
                $gender         = "female";
                break;
            case 2:
                $gender         = "male";
                break;
            default:
            case 3:
                $gender         = "other";
                break;
        }
        return $gender;
    }

    public function getLanguage($language){
        $language_id = Language::ENGLISH;
        //Gender
        switch($language){
            case 1:
                $language_id         = Language::PORTUGUESE;
                break;
            case 2:
                $language_id         = Language::ENGLISH;
                break;
            default:
            case 3:
                $language_id         = Language::SPANISH;
                break;
        }
        return $language_id;
    }

    public function getCurrencyId($currency){
        $currency_id = Currency::CURRENCY_IDS[Currency::REAL];
        if(isset(Currency::CURRENCY_IDS[$currency])){
            $currency_id = Currency::CURRENCY_IDS[$currency];
        }
        return $currency_id;
    }

    public function getType($type){
        //Gender
        switch($type){
            default:
            case "cliente":
                $type         = PersonType::FISICAL;
                break;
            case "agencia":
                $type         = PersonType::LEGAL;
                break;
        }
        return $type;
    }

    public function getCountry($countryIso3, $row){
        if(is_numeric($countryIso3) && in_array($row, ["id", "iso2"])){
            if(isset($this->_countriesId[$countryIso3]["id"])){
                return $this->_countriesId[$countryIso3][$row];
            }
        }
        if(isset($this->_countries[$countryIso3][$row])){
            return $this->_countries[$countryIso3][$row];
        }
        return null;
    }

    private function loadCountries(){
        $_countries = Country::get();
        foreach($_countries as $country){
            $this->_countries[$country->iso3]["id"]     = $country->id;
            $this->_countries[$country->iso3]["iso2"]   = $country->iso2;
        }
    }

    private function loadOldCountries(){
        $_oldCountries = DB::connection('mysql2')->table("am_paises")
        ->selectRaw('am_paises.*')
        ->orderBy("id", "asc")
        ->get();;
        foreach($_oldCountries as $country){
            $this->_countriesId[$country->id]["id"]     = $country->id;
            $this->_countriesId[$country->id]["iso2"]   = $country->iso;
        }
    }

    private function loadCountriesDdi(){
        $_countries = DB::connection('mysql2')->table("am_paises")
            ->selectRaw('am_paises.*')
            ->orderBy("id", "asc")
            ->get();;
        foreach($_countries as $country){
            $this->_countriesDdi[$country->id]["id"]     = $country->ddi;
        }
    }

    private function convertStatus($status){
        $defaultStatus  = ProcessStatus::PENDING;
        switch($status){
            case "bloqueada":
                $defaultStatus = ProcessStatus::BLOCKED;
                break;
            case "cortesia":
                $defaultStatus = ProcessStatus::COURTESY;
                break;
            case "cancelada":
                $defaultStatus = ProcessStatus::CANCELED;
                break;
            case "confirmada":
            case "confirmado":
                $defaultStatus = ProcessStatus::CONFIRMED;
                break;
            case "estornada":
            case "estornado":
                $defaultStatus = ProcessStatus::REFUNDED;
                break;
            case "liberado":
                $defaultStatus = ProcessStatus::RELEASED;
                break;
            case "parcialmentepago":
                $defaultStatus = ProcessStatus::PENDING;
                break;
            case "parcialmenterec":
                $defaultStatus = ProcessStatus::PARTIAL_RECEIVED;
                break;
            case "pendente":
                $defaultStatus = ProcessStatus::PENDING;
                break;
            case "pendenteconf":
                $defaultStatus = ProcessStatus::PENDING_CONFIRMATION;
                break;
            case "prereserva":
                $defaultStatus = ProcessStatus::PRERESERVED;
                break;
        }
        return $defaultStatus;
    }

    private function convertPackage($packageId, &$newPackageId, &$providerId){
        $providerId     = 1000;
        switch($packageId){
            case 998:   $newPackageId   = 1006; break;
            case 995:   $newPackageId   = 1001; break;
            case 1000:   $newPackageId   = 1014; break;
            case 1055:   $newPackageId   = 1027; break;
            case 1056:   $newPackageId   = 1027; break;
            case 1026:   $newPackageId   = 1007; break;
            case 1001:   $newPackageId   = 1013; break;
            case 1019:   $newPackageId   = 1011; break;
            case 1007:   $newPackageId   = 1000; break;
            case 1051:   $newPackageId   = 1004; break;
            case 1022:   $newPackageId   = 1002; break;
            case 1023:   $newPackageId   = 1003; break;
            case 1054:   $newPackageId   = 1016; break;
            case 996:   $newPackageId   = 1009; break;
            case 1025:   $newPackageId   = 1010; break;
            case 997:    $newPackageId   = 1008; break;
            case 1030:   $newPackageId   = 1005; break;
            case 1002:   $newPackageId   = 1012; break;
            case 1060:   $newPackageId   = 1025; break;
            case 1058:   $newPackageId   = 1024; break;
        }
    }

    public function getCity($cityName){
        $where  = "LOWER(name) = \"". strtolower($cityName) ."\"";
        $city = app(City::class)->whereRaw($where)
            ->first();
        if($city){
            return $city->id;
        }
        return $cityName;
    }

    private function loadOldPackages(){
        $_packages = DB::connection('mysql2')->table("am_pacotes")
            ->selectRaw('am_pacotes.*')
            ->get();;
        foreach($_packages as $package){
            $this->_packages[$package->id]  = $package->nome;
        }
    }

    private function getOldPackageName($packageId){
        $packageName    = null;
        if(isset($this->_packages[$packageId])){
            $packageName    = $this->_packages[$packageId];
        }
        return $packageName;
    }

    private function converPaymentMethod($oldPaymentMethodId){
        $paymentMethodId = null;
        switch($oldPaymentMethodId){
            case 10:
                $paymentMethodId = 27;
                break;
            case 11:
            case 12:
                $paymentMethodId = 28;
                break;
            case 13:
            case 14:
            case 15:
            case 16:
            case 17:
                $paymentMethodId = 25;
                break;
            case 18:
                $paymentMethodId = 29;
                break;
            case 19:
                $paymentMethodId = 28;
                break;
            case 24:
            case 25:
            case 27:
            case 29:
            case 31:
                $paymentMethodId = 25;
                break;
            case 35:
                $paymentMethodId = 30;
                break;
            case 36:
                $paymentMethodId = 27;
                break;
            case 21:
            case 22:
            case 23:
            case 26:
            case 28:
            case 30:
            case 32:
                $paymentMethodId = 36;
                break;
            case 33:
                $paymentMethodId = 34;
                break;
            case 34:
                $paymentMethodId = 33;
                break;
        }
        return $paymentMethodId;
    }

    private function getUserById($userId){
        switch($userId){
            case 1:
                $userId = 4;
                break;
            case 3:
                $userId = 5;
                break;
            case 8:
                $userId = 3;
                break;
            default:
                $userId = 1;
                break;
        }
        return $userId;
    }

    private function getUserByName($userName){
        $userId = 1;
        if(strpos($userName, "Roberto") !==false){
            $userId = 4;
        }
        if(strstr($userName, "Karla") !==false){
            $userId = 3;
        }
        if(strstr($userName, "Heloisa") !==false){
            $userId = 5;
        }
        return $userId;
    }

    private function handlePhones($user){
        $_phones = null;
        if($user->fone1 != ""){
            if(isset($this->_countriesDdi[$user->ddi1]["id"])){
                $phone = "+". $this->_countriesDdi[$user->ddi1]["id"];
            }else{
                $phone = "";
            }
            $phone .= $user->ddd1 . $user->fone1;
            $_phones[] = [
                'value' => convertFixUtf8($phone),
                'type'  => "mobile",
            ];
        }
        if($user->fone2 != ""){
            if(isset($this->_countriesDdi[$user->ddi2]["id"])){
                $phone = "+". $this->_countriesDdi[$user->ddi2]["id"];
            }else{
                $phone = "";
            }
            $phone .= $user->ddd2 . $user->fone2;
            $_phones[] = [
                'value' => convertFixUtf8($phone),
                'type'  => "mobile",
            ];
        }
        if($user->fone3 != ""){
            if(isset($this->_countriesDdi[$user->ddi3]["id"])){
                $phone = "+". $this->_countriesDdi[$user->ddi3]["id"];
            }else{
                $phone = "";
            }
            $phone .= $user->ddd3 . $user->fone3;
            $_phones[] = [
                'value' => convertFixUtf8($phone),
                'type'  => "mobile",
            ];
        }
        return $_phones;
    }

    private function handlePhone($user){
        $_phones = null;
        if(isset($this->_countriesDdi[$user->ddi]["id"])){
            $phone = "+". $this->_countriesDdi[$user->ddi]["id"];
        }else{
            $phone = "";
        }
        $user->fone = preg_replace("/^{$user->ddd}/", "", $user->fone);
        $phone .= $user->ddd . $user->fone;
        return $phone;
    }
}
