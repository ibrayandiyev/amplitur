<?php

namespace App\Services;

use App\Models\CurrencyQuotationHistory;
use App\Repositories\CurrencyQuotationRepository;
use App\Repositories\CurrencyRepository;
use Exception;
use Illuminate\Support\Facades\Http;

class QuotationService
{
    /**
     * @var CurrencyRepository
     */
    protected $currencyRepository;

    /**
     * @var CurrencyQuotationRepository
     */
    protected $currencyQuotationRepository;

    /**
     * @var string
     */
    protected $apiUri = 'https://economia.awesomeapi.com.br/json/last/';

    public function __construct(CurrencyRepository $currencyRepository, CurrencyQuotationRepository $currencyQuotationRepository)
    {
        $this->currencyRepository = $currencyRepository;
        $this->currencyQuotationRepository = $currencyQuotationRepository;
    }

    /**
     * [run description]
     *
     * @return  [type]  [return description]
     */
    public function run()
    {
        $pairs = $this->getCurrencyPairs();
        $this->getQuotations($pairs);
    }

    /**
     * [getCurrencyPairs description]
     *
     * @return  array   [return description]
     */
    public function getCurrencyPairs(): array
    {
        $currencies = $this->currencyRepository->list();
        $pairs = [];

        foreach ($currencies as $originCurrency) {
            foreach ($currencies as $targetCurrency) {
                if ($originCurrency->id == $targetCurrency->id) {
                    continue;
                }

                $pairs[] = "{$targetCurrency->code}-{$originCurrency->code}";
            }
        }

        return $pairs;
    }

    /**
     * [getQuotations description]
     *
     * @param   array  $pairs  [$pairs description]
     *
     * @return  [type]         [return description]
     */
    public function getQuotations(array $pairs)
    {
        foreach ($pairs as $pair) {
            $response = Http::get($this->apiUri . $pair);

            if ($response->failed()) {
                bugtracker()->notifyError('[QuotationService] Failed to catch quotation', 'Failed to catch quotation', [
                    'pair' => ['pair' => $pair],
                    'response' => [
                        'body' => $response->body(),
                        'status' => $response->status(),
                        'headers' => $response->headers(),
                    ],
                ]);

                continue;
            }

            try {
                $quotation = $response->collect()->first();
                $originCurrency = $this->currencyRepository->findByCode($quotation['codein']);
                $targetCurrency = $this->currencyRepository->findByCode($quotation['code']);
                $currencyQuotations = $this->currencyQuotationRepository->updateQuotationValue($originCurrency, $targetCurrency, $quotation['ask']);

                foreach ($currencyQuotations as $currencyQuotation) {
                    CurrencyQuotationHistory::create([
                        'origin_currency_id' => $currencyQuotation->origin_currency_id,
                        'target_currency_id' => $currencyQuotation->target_currency_id,
                        'quotation' => $currencyQuotation->quotation,
                        'spread' => $currencyQuotation->spread,
                    ]);
                }

            } catch (Exception $ex) {
                bugtracker()->notifyException($ex);
                continue;
            }
        }
    }
}