<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\CurrencyQuotation;
use App\Repositories\CurrencyQuotationRepository;
use App\Repositories\CurrencyRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    /**
     * @var CurrencyRepository
     */
    protected $repository;

    /**
     * @var CurrencyQuotationRepository
     */
    protected $currencyQuotationRepositoy;

    public function __construct(CurrencyRepository $repository, CurrencyQuotationRepository $currencyQuotationRepositoy)
    {
        $this->repository = $repository;
        $this->currencyQuotationRepositoy = $currencyQuotationRepositoy;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Currency::class);

        try {
            $currencies = $this->repository->list();

            return view('backend.currencies.index')
                ->with('currencies', $currencies);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, CurrencyQuotation $currencyQuotation)
    {
        $this->authorize('manage', Currency::class);

        try {
            $attributes = $request->all();

            if (isset($attributes['created_at'])) {
                $date = Carbon::createFromFormat('d-m-Y', $attributes['created_at']);
            } else {
                $date = null;
            }

            $currencyQuotationHistory = $this->currencyQuotationRepositoy->getHistory($currencyQuotation, $date ? $date->format('Y-m-d') : null);
            
            return view('backend.currencies.edit')
                ->with('currencyQuotation', $currencyQuotation)
                ->with('currencyQuotationHistory', $currencyQuotationHistory)
                ->with('date', $date ? $date->format('d/m/Y') : null);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.currencies.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request      $request
     * @param  Currency  $currency
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->authorize('manage', Currency::class);

        try {
            $attributes = $request->toArray();

            $this->currencyQuotationRepositoy->updateBatch($attributes['currency']);

            return redirect()->route('backend.currencies.index')->withSuccess(__('resources.currencies.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.currencies.index')->withError($ex->getMessage());
        }
    }
}
