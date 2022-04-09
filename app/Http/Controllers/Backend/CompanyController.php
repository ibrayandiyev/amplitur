<?php

namespace App\Http\Controllers\Backend;

use App\Enums\ProcessStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CompanyRequest;
use App\Models\Company;
use App\Models\Provider;
use App\Repositories\BankRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\StateRepository;
use Exception;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * @var CompanyRepository
     */
    protected $repository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var StateRepository
     */
    protected $stateRepository;

    /**
     * @var BankRepository
     */
    protected $bankRepository;

    public function __construct(CompanyRepository $repository, CountryRepository $countryRepository, StateRepository $stateRepository, BankRepository $bankRepository)
    {
        $this->repository = $repository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->bankRepository = $bankRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @param Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function index(Provider $provider)
    {
        try {
            $companies = $this->repository->setProvider($provider)->list();

            return view('backend.companies.index', compact('companies', 'provider'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Provider $provider)
    {
        try {
            $countries = $this->countryRepository->list();
            $brazilianBanks = $this->bankRepository->list();

            return view('backend.companies.create', compact('countries', 'provider', 'brazilianBanks'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.index', $provider)->withError($ex->getMessage())->withInputs($request->all());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CompanyRequest  $request
     * @param  Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function store(CompanyRequest $request, Provider $provider)
    {
        try {
            if(!$this->checkAllow('view', [Provider::class, $provider])){
                $this->authorize('manage', Provider::class);
            }
            $attributes = $request->toArray();
            if(!user()->canManageProviders()){
                $attributes['status']   = ProcessStatus::IN_ANALYSIS;
            }

            $company = $this->repository
                ->setProvider($provider)
                ->setUploadedDocuments($request->allFiles())
                ->store($attributes);

            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withSuccess(__('resources.companies.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.create', $provider)->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Provider  $provider
     * @param  Company   $company
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider, Company $company)
    {
        
        try {
            if(!$this->checkAllow('view', [Provider::class, $provider])){
                $this->authorize('manage', Provider::class);
            }
            $address    = $company->address;
            $countries  = $this->countryRepository->list();
            $brazilianBanks = $this->bankRepository->list();
            $documents = $company->documents;
            $offers = $company->offers;

            return view('backend.companies.edit', compact('company', 'provider', 'address', 'countries', 'brazilianBanks', 'documents', 'offers'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.index', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CompanyRequest $request
     * @param  Provider  $provider
     * @param  Company   $company
     * @return \Illuminate\Http\Response
     */
    public function update(CompanyRequest $request, Provider $provider, Company $company)
    {

        try {
            if(!$this->checkAllow('view', [Provider::class, $provider])){
                $this->authorize('manage', Provider::class);
            }
            $attributes = $request->toArray();

            $company = $this->repository
                ->setProvider($provider)
                ->setUploadedDocuments($request->allFiles())
                ->update($company, $attributes);

            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withSuccess(__('resources.companies.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.edit', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Provider  $provider
     * @param  Company   $company
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider, Company $company)
    {

        try {
            if(!$this->checkAllow('view', [Provider::class, $provider])){
                $this->authorize('manage', Provider::class);
            }
            $company = $this->repository->setProvider($provider)->delete($company);

            return redirect()->route('backend.providers.companies.index', $provider)->withSuccess(__('resources.companies.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.index', $provider)->withError($ex->getMessage());
        }
    }
}
