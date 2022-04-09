<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\Language;
use App\Http\Controllers\Controller;
use App\Repositories\CurrencyRepository;
use Illuminate\Http\Request;

class CurrencyLanguageController extends Controller
{
    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    public function __construct(CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * [changeCurrency description]
     *
     * @param   string  $currency  [$currency description]
     *
     * @return  [type]             [return description]
     */
    public function changeCurrency(string $currency)
    {
        $currency = $this->currencyRepository->findByCode($currency);

        if (empty($currency)) {
            return redirect()->route('frontend.index')->withError('Currency doesn\'t exists');
        }

        currency($currency);

        return back();
    }

    /**
     * [changeLanguage description]
     *
     * @param   string  $language  [$currency description]
     *
     * @return  [type]             [return description]
     */
    public function changeLanguage(string $language)
    {
        $languages = Language::toArray();

        if (!in_array($language, $languages)) {
            return redirect()->route('frontend.index')->withError('Language doesn\'t exists');
        }

        language($language);

        return back();
    }
}
