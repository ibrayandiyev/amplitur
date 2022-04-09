<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\PackageRequest;
use App\Models\Package;
use App\Repositories\CompanyRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\CountryRepository;
use App\Repositories\EventRepository;
use App\Repositories\PackageRepository;
use App\Repositories\PaymentMethodRepository;
use Exception;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    /**
     * @var PackageRepository
     */
    protected $repository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var ProviderRepository
     */
    protected $providerRepository;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * @var PaymentMethodRepository
     */
    protected $paymentMethodRepository;

    public function __construct(PackageRepository $repository,
                                CountryRepository $countryRepository,
                                ProviderRepository $providerRepository,
                                EventRepository $eventRepository,
                                CompanyRepository $companyRepository,
                                PaymentMethodRepository $paymentMethodRepository)
    {
        $this->repository = $repository;
        $this->countryRepository = $countryRepository;
        $this->providerRepository = $providerRepository;
        $this->eventRepository = $eventRepository;
        $this->companyRepository = $companyRepository;
        $this->paymentMethodRepository = $paymentMethodRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('manage', Package::class);

        try {
            $params     = $request->toArray();

            $packages = $this->repository->list();

            if(!isset($params["package_id"])){
                $params["package_id"]   = -1;
            }

            return view('backend.packages.index', compact('packages'))
                ->with("_params", $params)
            ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->authorize('manage', Package::class);

        try {
            $provider   = $this->providerRepository->find($request->get('provider_id'));
            $event      = $this->eventRepository->find($request->get('event_id'));
            $company    = $this->companyRepository->find($request->get('company_id'));
            $offerType  = $request->get('offerType');
            $countries  = $this->countryRepository->list();
            $providers  = $this->providerRepository->list();

            return view('backend.packages.create')
                ->with('countries', $countries)
                ->with('provider', $provider)
                ->with('event', $event)
                ->with('providers', $providers)
                ->with('company', $company)
                ->with('offerType', $offerType);

        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PackageRequest $request)
    {
        $this->authorize('manage', Package::class);

        try {
            $attributes = $request->toArray();

            $attributes['starts_at']    = convertDatetime($attributes['starts_at']);
            $attributes['ends_at']      = convertDatetime($attributes['ends_at']);
            $attributes['ends_at']      = checkPackageStartDateTime($attributes['starts_at'], $attributes['ends_at']);

            if(!isset($attributes['provider_id'])){
                $attributes['provider_id'] = auth('providers')->user()->id;
            }

            $event      = $this->eventRepository->find($attributes['event_id']);
            $provider   = $this->providerRepository->find($attributes['provider_id']);
            $company    = $this->companyRepository->find($request->get('company_id'));
            $package    = $this->repository->setEvent($event)->store($attributes);

            if (!empty($request->get('offerType'))) {
                return redirect()->route('backend.providers.companies.offers.create', [
                    $provider,
                    $company,
                    'event_id' => $event->id,
                    'type' => $request->get('offerType'),
                    'package_id' => $package->id,
                ]);
            }

            return redirect()->route('backend.packages.edit', $package)->withSuccess(__('resources.packages.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->back()->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Package $package)
    {

        $this->authorize('see', $package);

        try {
            $countries = $this->countryRepository->list();
            $providers = $this->providerRepository->list();
            $offers = $package->offers;

            $paymentMethods['national'] = $this->paymentMethodRepository->setPackage($package)->getNationals();
            $paymentMethods['international'] = $this->paymentMethodRepository->setPackage($package)->getInternationals();
            $billetPaymentMethods = $this->paymentMethodRepository->getBilletPaymentMethods();

            return view('backend.packages.edit')
                ->with('event', $package->event)
                ->with('provider', $package->provider)
                ->with('package', $package)
                ->with('countries', $countries)
                ->with('offers', $offers)
                ->with('paymentMethods', $paymentMethods)
                ->with('billetPaymentMethods', $billetPaymentMethods)
                ->with('providers', $providers);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(PackageRequest $request, Package $package)
    {
        if($this->checkAllow("onlyProvider", Provider::class)){
            $provider = auth()->user('providers');
            return redirect()->route('backend.packages.index')->withErrors(__('resources.packages.not_updated'));;
        }else{
            $this->authorize('update', $package);
        }

        try {
            $attributes = $request->toArray();

            $attributes['starts_at']    = convertDatetime($attributes['starts_at']);
            $attributes['ends_at']      = convertDatetime($attributes['ends_at']);
            $attributes['ends_at']      = checkPackageStartDateTime($attributes['starts_at'], $attributes['ends_at']);

            $package = $this->repository->setEvent($package->event)->update($package, $attributes);

            return redirect()->route('backend.packages.edit', $package->id)->withSuccess(__('resources.packages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.edit', $package->id)->withError($ex->getMessage());
        }
    }

    /**
     * Show package offers
     *
     * @param  Request $request
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function offers(Request $request, Package $package)
    {
        try {
            $offers = $this->repository->getOffers($package, 'provider_id');

            return view('backend.packages.offers')
                ->with('package', $package)
                ->with('offers', $offers);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.index', $package->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        $this->authorize('delete', $package);

        try {
            $package = $this->repository->delete($package);

            return redirect()->route('backend.packages.index')->withSuccess(__('resources.packages.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.index')->withError($ex->getMessage());
        }
    }

    /**
     * [destroyPaymentMethod description]
     *
     * @param   Package  $package  [$package description]
     * @param   int      $id       [$id description]
     *
     * @return  [type]             [return description]
     */
    public function destroyPaymentMethod(Package $package, int $id)
    {
        if (!user()->canSeePackagePaymentMethods()) {
            forbidden();
        }

        try {
            $this->repository->removePaymentMethod($package, $id);

            return redirect()->route('backend.packages.edit', $package)->withSuccess(__('resources.packages.paymentMethodDeleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withErro($ex->getMessage());
        }
    }

    /**
     * [createPaymentMethod description]
     *
     * @param   Package  $package  [$package description]
     *
     * @return  [type]             [return description]
     */
    public function createPaymentMethod(Package $package)
    {
        if (!user()->canSeePackagePaymentMethods()) {
            forbidden();
        }

        try {
            $paymentMethods['national'] = $this->paymentMethodRepository->getNationals();
            $paymentMethods['international'] = $this->paymentMethodRepository->getInternationals();
            $billetPaymentMethods = $this->paymentMethodRepository->getBilletPaymentMethods();

            return view('backend.packages.paymentMethods.create')
                ->with('package', $package)
                ->with('billetPaymentMethods', $billetPaymentMethods)
                ->with('paymentMethods', $paymentMethods);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withErro($ex->getMessage());
        }
    }

    /**
     * [storePaymentMethod description]
     *
     * @param   Request  $request  [$request description]
     * @param   Package  $package  [$package description]
     *
     * @return  [type]             [return description]
     */
    public function storePaymentMethod(Request $request, Package $package)
    {
        if (!user()->canSeePackagePaymentMethods()) {
            forbidden();
        }

        try {
            $attributes = $request->all();

            $this->repository->addPaymentMethod($package, $attributes['payment_method_id'], $attributes);

            return redirect()->route('backend.packages.edit', $package)->withSuccess(__('resources.packages.paymentMethodCreated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withInput($attributes)->withError($ex->getMessage());
        }
    }

    /**
     * Filter the specified resource from storage
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        try {
            $package          = $this->packageRepository->setActor(user())->list();
            $this->_params = $request->except(['_token']);

            $name   = $this->packageRepository->filter($this->_params, $this->per_page);
            return view('backend.bookings.index')
                ->with('bookings', $name)
                ->with('_params', $this->_params);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.bookings.index')->withError($ex->getMessage());
        }
    }
}
