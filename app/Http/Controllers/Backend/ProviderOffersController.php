<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OfferType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProviderOfferRequest;
use App\Models\Company;
use App\Models\Image;
use App\Models\Offer;
use App\Models\Package;
use App\Models\Provider;
use App\Repositories\AdditionalGroupRepository;
use App\Repositories\AdditionalRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\CompanyRepository;
use App\Repositories\CountryRepository;
use App\Repositories\EventRepository;
use App\Repositories\HotelStructureRepository;
use App\Repositories\ImageRepository;
use App\Repositories\ObservationRepository;
use App\Repositories\OfferRepository;
use App\Repositories\PackageRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\SaleCoefficientRepository;
use App\Services\OfferReplicationService;
use Exception;
use Illuminate\Http\Request;

class ProviderOffersController extends Controller
{
    /**
     * @var ProviderRepository
     */
    protected $providerRepository;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * @var OfferRepository
     */
    protected $offerRepository;

    /**
     * @var EventRepository
     */
    protected $eventRepository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var SaleCoefficientRepository
     */
    protected $saleCoefficientRepository;

    /**
     * @var AdditionalRepository
     */
    protected $additionalRepository;

    /**
     * @var AdditionalGroupRepository
     */
    protected $additionalGroupRepository;

    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * @var HotelStructureRepository
     */
    protected $hotelStructureRepository;

    /**
     * @var PackageRepository
     */
    protected $packageRepository;

    /**
     * @var ObservationRepository
     */
    protected $observationRepository;

    /**
     * @var ImageRepository
     */
    protected $imageRepository;

    public function __construct(ProviderRepository $providerRepository,
                                CompanyRepository $companyRepository,
                                OfferRepository $offerRepository,
                                EventRepository $eventRepository,
                                CountryRepository $countryRepository,
                                SaleCoefficientRepository $saleCoefficientRepository,
                                AdditionalRepository $additionalRepository,
                                AdditionalGroupRepository $additionalGroupRepository,
                                CategoryRepository $categoryRepository,
                                HotelStructureRepository $hotelStructureRepository,
                                ImageRepository $imageRepository,
                                PackageRepository $packageRepository,
                                ObservationRepository $observationRepository
                                )
    {
        $this->providerRepository = $providerRepository;
        $this->companyRepository = $companyRepository;
        $this->offerRepository = $offerRepository;
        $this->eventRepository = $eventRepository;
        $this->countryRepository = $countryRepository;
        $this->saleCoefficientRepository = $saleCoefficientRepository;
        $this->additionalRepository = $additionalRepository;
        $this->additionalGroupRepository = $additionalGroupRepository;
        $this->packageRepository = $packageRepository;
        $this->hotelStructureRepository = $hotelStructureRepository;
        $this->imageRepository = $imageRepository;
        $this->categoryRepository = $categoryRepository;
        $this->observationRepository = $observationRepository;
    }

    public function index(Provider $provider, Company $company)
    {
        try {
            $offers = $this->offerRepository->setActor(user())->setCompany($company)->list();

            return view('backend.offers.index', compact('offers', 'provider', 'company'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * Show the form for prepare to create an offer
     *
     * @param   Provider  $provider
     * @param   Company   $company
     * @param   Request   $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function prepare(Provider $provider, Company $company, Request $request)
    {
        try {
            return view('backend.offers.prepare')
                ->with('provider', $provider)
                ->with('company', $company);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.index', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param  Provider     $provider
     * @param  Company      $company
     * @return \Illuminate\Http\Response
     */
    public function create(Provider $provider, Company $company, Request $request)
    {
        if (!user()->canCreateProviderCompanyOffer($provider, $company)) {
            forbidden();
        }

        try {
            $event = $this->eventRepository->find($request->get('event_id'));
            $selectedPackage = $this->packageRepository->find($request->get('package_id'));
            $packages = $this->packageRepository->setEvent($event)->getAvailables();
            $defaultSaleCoefficient = $this->saleCoefficientRepository->getDefaultCoefficient();
            $type = $request->get('type');

            if (empty($selectedPackage)) {
                return redirect()->route('backend.packages.create', [
                    'event_id' => $event->id,
                    'provider_id' => $provider->id,
                    'company_id' => $company->id,
                    'offerType' => $type,
                ]);
            }

            return view('backend.offers.create')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('packages', $packages)
                ->with('selectedPackage', $selectedPackage)
                ->with('defaultSaleCoefficient', $defaultSaleCoefficient)
                ->with('event', $event)
                ->with('type', $type);

        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.prepare', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProviderOfferRequest $request
     * @param  Provider     $provider
     * @param  Company      $company
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderOfferRequest $request, Provider $provider, Company $company)
    {
        if (!user()->canCreateProviderCompanyOffer($provider, $company)) {
            forbidden();
        }

        try {
            $attributes = $request->all();
            $attributes['expires_at'] = convertDatetime($attributes['expires_at']);

            if (!user()->canManageOfferSaleCoefficient()) {
                $attributes['sale_coefficient_id'] = $this->saleCoefficientRepository->getDefaultCoefficient()->id;
            }

            $offer = $this->offerRepository->setCompany($company)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('resources.offers.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Provider     $provider
     * @param  Company      $company
     * @param  Offer        $offer
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        $this->authorize('update', $offer);

        try {
            $packages           = $this->packageRepository->setEvent($offer->package->event)->getAvailables();
            $saleCoefficients   = $this->saleCoefficientRepository->listOrdered();
            $additionals        = $this->packageRepository->getAdditionals($offer);
            $additionalables    = $this->offerRepository->getAdditionalables($offer);
            $storedAdditionals  = $this->offerRepository->getAdditionals($offer);
            $navigation         = $request->get('navigation', '');

            if ($offer->isHotel()) {
                $hotel      = null;
                if($offer->hotelOffer->hotel != null){
                    $hotel = $offer->hotelOffer->hotel()->first();
                }
                $hotelOffer = $offer->hotelOffer;
                $countries = $this->countryRepository->list();
                $hotelCategories = $this->categoryRepository->listHotel();
                $hotelStructures = $this->hotelStructureRepository->list();
                $hotelAccommodations = $offer->hotelOffer->accommodations;
                $observations = $this->observationRepository->setType(OfferType::HOTEL)->list();
            }

            if ($offer->isAdditional()) {
                $additionalItems = $this->offerRepository->getAdditionalItems($offer);
                $additionalGroups = $this->offerRepository->getAdditionalGroups($offer);
            }

            return view('backend.offers.edit')
                ->with('offer', $offer)
                ->with('company', $company)
                ->with('provider', $provider)
                ->with('saleCoefficients', $saleCoefficients)
                ->with('additionals', $additionals)
                ->with('storedAdditionals', $storedAdditionals)
                ->with('additionalables', $additionalables)
                ->with('hotelCategories', $hotelCategories ?? null)
                ->with('hotelStructures', $hotelStructures ?? null)
                ->with('hotelAccommodations', $hotelAccommodations ?? null)
                ->with('countries', $countries ?? null)
                ->with('hotel', $hotel ?? null)
                ->with('hotelOffer', $hotelOffer ?? null)
                ->with('observations', $observations ?? null)
                ->with('additionalItems', $additionalItems ?? null)
                ->with('additionalGroups', $additionalGroups ?? null)
                ->with('packages', $packages)
                ->with('navigation', $navigation)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.index', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProviderOfferRequest $request
     * @param  Provider     $provider
     * @param  Company      $company
     * @param  Offer        $offer
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderOfferRequest $request, Provider $provider, Company $company, Offer $offer)
    {
        $this->authorize('update', $offer);
        try {
            $attributes         = $request->toArray();
            $attributes['expires_at'] = convertDatetime($attributes['expires_at']);
            $navigation         = $request->get('navigation', 'sales-info');


            if($offer->currency != $attributes['currency']){
                if(!user()->canManageOfferCurrency()){
                    $attributes['currency'] = $offer->currency;
                }
            }

            if (!user()->canManageOfferSaleCoefficient()) {
                unset($attributes['sale_coefficient_id']);
            }

            $offer = $this->offerRepository->update($offer, $attributes);

            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => $navigation])->withSuccess(__('messages.updated'))->withInput();
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', ['provider' => $provider, 'company' => $company, 'offer' => $offer, 'navigation' => $navigation])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Provider     $provider
     * @param  Company      $company
     * @param  Offer        $offer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider, Company $company, Offer $offer)
    {
        $this->authorize('delete', $offer);

        try {
            $offer = $this->offerRepository->delete($offer);

            return redirect()->route('backend.providers.companies.offers.index', [$provider, $company])->withSuccess(__('resources.offers.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.index', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * [storeReplicate description]
     *
     * @param   Request  $request  [$request description]
     * @param   Offer    $offer    [$offer description]
     *
     * @return  [type]             [return description]
     */
    public function storeReplicate(Request $request, Offer $offer)
    {
        $this->authorize('replicate', $offer);

        try {
            $attributes = $request->toArray();
            $service = app(OfferReplicationService::class);

            $package = $this->packageRepository->find($attributes['package_id']);
            $provider = $this->providerRepository->find($attributes['provider_id']);
            $company = $this->companyRepository->find($attributes['company_id']);

            if (empty($package) || empty($provider) || empty($company)) {
                return back()->withError('Ocorreu um erro ao tentar replicar esta oferta');
            }

            $newOffer = $service->replicate($offer, $package, $provider, $company, $request);

            return redirect()->route('backend.providers.companies.offers.edit', [$newOffer->provider_id, $newOffer->company, $newOffer])->withSuccess(__('resources.offers.replicated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->back()->withError($ex->getMessage());
        }
    }

   /**|
    * [replicate description]
    *
    * @param   Provider  $provider  [$provider description]
    * @param   Company   $company   [$company description]
    * @param   Offer     $offer     [$offer description]
    *
    * @return  [type]               [return description]
    */
    public function replicate(Request $request, Offer $offer)
    {
        $this->authorize('replicate', $offer);

        try {
            $attributes = $request->toArray();

            if (isset($attributes['provider']) && isset($attributes['company'])) {
                $provider = $this->providerRepository->find($attributes['provider']);
                $company = $this->companyRepository->setActor(user())->find($attributes['company']);
                $additionalables = $this->offerRepository->getAdditionalables($offer);
                $additionals = $this->packageRepository->getAdditionals($offer);


                return view('backend.offers.replicate-prepare')
                    ->with('provider', $provider)
                    ->with('company', $company)
                    ->with('additionalables', $additionalables)
                    ->with('additionals', $additionals)
                    ->with('offer', $offer);
            }

            $provider = auth('providers')->user();
            $companies = $this->companyRepository->listActives($provider);

            return view('backend.offers.replicate-company')
                ->with('companies', $companies)
                ->with('offer', $offer)
                ->with('company', null)
                ->with('provider', null);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    /**|
    * [replicate description]
    *
    * @param   Provider  $provider  [$provider description]
    * @param   Company   $company   [$company description]
    * @param   Offer     $offer     [$offer description]
    *
    * @return  [type]               [return description]
    */
    public function replicate_extra(Request $request, Offer $offer, Package $package)
    {
        $this->authorize('replicate', $offer);

        try {
            $attributes = $request->toArray();

            if (isset($attributes['provider']) && isset($attributes['company'])) {
                $provider = $this->providerRepository->find($attributes['provider']);
                $company = $this->companyRepository->setActor(user())->find($attributes['company']);
                $additionalables = $this->offerRepository->getAdditionalables($offer);
                $additionals = $this->packageRepository->getAdditionals($offer);

                return view('backend.offers.replicate-prepare')
                    ->with('provider', $provider)
                    ->with('company', $company)
                    ->with('additionalables', $additionalables)
                    ->with('additionals', $additionals)
                    ->with('offer', $offer);
            }

        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->back()->withError($ex->getMessage());
        }
    }

    /**
     * [galleryCreateImage description]
     *
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function galleryCreateImage(Provider $provider, Company $company, Offer $offer)
    {
        $this->authorize('manage-image', $offer);

        try {
            return view('backend.offers.gallery.create')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [galleryStoreImage description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function galleryStoreImage(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        $this->authorize('manage-image', $offer);

        try {
            $attributes = $request->toArray();
            $attributes['provider_id'] = $provider->id;
            $attributes['offer_id'] = $offer->id;
            $attributes['type'] = 'offer';
            $attributes['image'] = $request->file('image');

            $image = $this->imageRepository->store($attributes);

            return redirect()->route('backend.providers.companies.offers.gallery.edit', [$provider, $company, $offer, $image])->withSuccess(__('resources.images.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.gallery.create', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [galleryEditImage description]
     *
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     * @param   Image     $image     [$image description]
     *
     * @return  [type]               [return description]
     */
    public function galleryEditImage(Provider $provider, Company $company, Offer $offer, Image $image)
    {
        $this->authorize('manage-image', $offer);

        try {
            return view('backend.offers.gallery.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('image', $image);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [galleryUpdateImage description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     * @param   Image     $image     [$image description]
     *
     * @return  [type]               [return description]
     */
    public function galleryUpdateImage(Request $request, Provider $provider, Company $company, Offer $offer, Image $image)
    {
        $this->authorize('manage-image', $offer);

        try {
            $attributes = $request->toArray();
            $attributes['image'] = $request->file('image');

            $image = $this->imageRepository->update($image, $attributes);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [galleryDestroyImage description]
     *
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     * @param   Image     $image     [$image description]
     *
     * @return  [type]               [return description]
     */
    public function galleryDestroyImage(Provider $provider, Company $company, Offer $offer, Image $image)
    {
        $this->authorize('manage-image', $offer);

        try {
            $image = $this->imageRepository->delete($image);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('resources.images.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider,$company, $offer])->withError($ex->getMessage());
        }
    }
}
