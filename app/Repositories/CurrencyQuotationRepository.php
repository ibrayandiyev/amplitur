<?php

namespace App\Repositories;

use App\Models\Currency;
use App\Models\CurrencyQuotation;
use App\Models\CurrencyQuotationHistory;
use Illuminate\Support\Collection;

class CurrencyQuotationRepository extends Repository
{
    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    public function __construct(CurrencyQuotation $model, CurrencyRepository $currencyRepository)
    {
        $this->model = $model;
        $this->currencyRepository = $currencyRepository;
    }

    /**
     * [convert description]
     *
     * @param   [type]  $value           [$value description]
     * @param   [type]  $originCurrency  [$originCurrency description]
     * @param   [type]  $targetCurrency  [$targetCurrency description]
     *
     * @return  float                    [return description]
     */
    public function convert($value, $originCurrency, $targetCurrency): ?float
    {
        if (!$originCurrency instanceof Currency) {
            $originCurrency = $this->currencyRepository->findByCode($originCurrency);
        }

        if (!$targetCurrency instanceof Currency) {
            $targetCurrency = $this->currencyRepository->findByCode($targetCurrency);
        }

        if (empty($originCurrency) || empty($targetCurrency)) {
            return null;
        }

        $quotation = $this->model
            ->where('origin_currency_id', $originCurrency->id)
            ->where('target_currency_id', $targetCurrency->id)
            ->first();

        if (empty($quotation)) {
            return null;
        }

        $convertedValue = ($value / $quotation->quotation) * $quotation->spread;

        return $convertedValue;
    }

    /**
     * [getHistory description]
     *
     * @param   CurrencyQuotation  $currencyQuotation  [$currencyQuotation description]
     * @param   string             $date               [$date description]
     *
     * @return  Collection                             [return description]
     */
    public function getHistory(CurrencyQuotation $currencyQuotation, ?string $date): ?Collection
    {
        $query = CurrencyQuotationHistory::where('origin_currency_id', $currencyQuotation->origin_currency_id)
            ->where('target_currency_id', $currencyQuotation->target_currency_id);

        if (!empty($date)) {
            $query = $query->whereDate('created_at', $date);
        }

        $query = $query->orderByDesc('created_at');

        $currencyQuotationHistory = $query->get();

        return $currencyQuotationHistory;
    }

    /**
     * [updateQuotationValue description]
     *
     * @param   [type]  $originCurrency  [$originCurrency description]
     * @param   [type]  $targetCurrency  [$targetCurrency description]
     * @param   [type]  $quotation       [$quotation description]
     *
     * @return  CurrencyQuotation        [return description]
     */
    public function updateQuotationValue($originCurrency, $targetCurrency, $quotation): ?Collection
    {
        $currenciesQuotations = $this->model
            ->where('origin_currency_id', $originCurrency->id)
            ->where('target_currency_id', $targetCurrency->id)
            ->get();

        if (empty($currenciesQuotations)) {
            return null;
        }

        foreach ($currenciesQuotations as $currencyQuotation) {
            $currencyQuotation->quotation = $quotation;
            $currencyQuotation->save();
        }

        return $currenciesQuotations;
    }
}