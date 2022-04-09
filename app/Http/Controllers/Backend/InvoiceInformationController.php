<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\InvoiceInformation;
use App\Repositories\CurrencyRepository;
use App\Repositories\InvoiceInformationRepository;
use Exception;
use Illuminate\Http\Request;

class InvoiceInformationController extends Controller
{
    /**
     * @var CurrencyRepository
     */
    protected $repositoryCurrencies;

    /**
     * @var InvoiceInformationRepository
     */
    protected $repository;

    public function __construct(InvoiceInformationRepository $repository, CurrencyRepository $repositoryCurrencies)
    {
        $this->repository = $repository;
        $this->repositoryCurrencies = $repositoryCurrencies;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', InvoiceInformation::class);

        try {
            $invoiceInformation = $this->repository->list();

            return view('backend.invoiceInformation.index')
                ->with('invoiceInformation', $invoiceInformation);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('manage', InvoiceInformation::class);

        try {
            $currencies = $this->repositoryCurrencies->list();
            return view('backend.invoiceInformation.create')
            ->with('currencies', $currencies)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.invoiceInformation.index', [])->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', InvoiceInformation::class);

        try {
            $attributes = $request->toArray();
            $invoiceInformation = $this->repository->store($attributes);

            return redirect()->route('backend.invoiceInformation.edit', [$invoiceInformation])->withSuccess(__('resources.invoiceInformation.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.invoiceInformation.create', [])->withError($ex->getMessage())->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  InvoiceInformation  $invoiceInformation
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, InvoiceInformation $invoiceInformation)
    {
        $this->authorize('manage', InvoiceInformation::class);

        try {
            $currencies = $this->repositoryCurrencies->list();

            return view('backend.invoiceInformation.edit')
                ->with('currencies', $currencies)

                ->with('invoiceInformation', $invoiceInformation);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.invoiceInformation.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  InvoiceInformation  $invoiceInformation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, InvoiceInformation $invoiceInformation)
    {
        $this->authorize('manage', InvoiceInformation::class);

        try {
            $attributes = $request->toArray();
            $invoiceInformation = $this->repository->update($invoiceInformation, $attributes);

            return redirect()->route('backend.invoiceInformation.edit', [$invoiceInformation])->withSuccess(__('resources.invoiceInformation.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.invoiceInformation.edit', [$invoiceInformation])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request    $request
     * @param  string     $type
     * @param  InvoiceInformation  $invoice_information
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, InvoiceInformation $invoice_information)
    {
        $this->authorize('manage', InvoiceInformation::class);
        try {
            $invoiceInformation = $this->repository->delete($invoice_information);

            return redirect()->route('backend.invoiceInformation.index', [])->withSuccess(__('resources.invoiceInformation.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.invoiceInformation.index', [])->withError($ex->getMessage());
        }
    }
}
