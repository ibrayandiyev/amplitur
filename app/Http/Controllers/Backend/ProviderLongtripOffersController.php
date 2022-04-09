<?php

namespace App\Http\Controllers\Backend;

use App\Base\BaseController;
use App\Enums\OfferType;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Provider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Offers\LongTripAccommodationHotelRequest;
use App\Http\Requests\Backend\Offers\LongTripAccommodationRequest;
use App\Http\Requests\Backend\Offers\LongTripAccommodationTypeRequest;
use App\Http\Requests\Backend\Offers\LongTripRouteUpdateRequest;
use App\Models\LongtripAccommodation;
use App\Models\LongtripAccommodationHotel;
use App\Models\LongtripBoardingLocation;
use App\Models\LongtripRoute;
use App\Repositories\AdditionalRepository;
use App\Repositories\CategoryRepository;
use App\Repositories\LongtripBoardingLocationRepository;
use App\Repositories\LongtripRouteRepository;
use App\Repositories\CountryRepository;
use App\Repositories\ExclusionRepository;
use App\Repositories\HotelRepository;
use App\Repositories\InclusionRepository;
use App\Repositories\LongtripAccommodationHotelRepository;
use App\Repositories\LongtripAccommodationRepository;
use App\Repositories\LongtripAccommodationsPricingRepository;
use App\Repositories\LongtripAccommodationTypeRepository;
use App\Repositories\LongtripHotelLabelRepository;
use App\Repositories\ObservationRepository;
use Exception;
use Illuminate\Http\Request;

/**
 *
 * @package App\Http\Controllers\Backend
 *
 * Rule 090921 - Remove the create and edit forms for the LongtripOfferAccommodationHotel.
 */
class ProviderLongtripOffersController extends BaseController
{
    /**
     * @var LongtripRouteRepository
     */
    public $longtripRouteRepository;

    /**
     * @var LongtripBoardingLocationRepository
     */
    public $longtripBoardingLocationRepository;

    /**
     * @var LongtripHotelLabelRepository
     */
    public $longtripHotelLabelRepository;

    /**
     * @var CountryRepository
     */
    public $countryRepository;

    /**
     * @var InclusionRepository
     */
    public $inclusionGroupRepository;

    /**
     * @var ExclusionRepository
     */
    public $exclusionGroupRepository;

    /**
     * @var AdditionalRepository
     */
    public $additionalRepository;

    /**
     * @var ObservationRepository
     */
    public $observationRepository;

    /**
     * @var LongtripAccommodationRepository
     */
    public $longtripAccommodationRepository;

    /**
     * @var LongtripAccommodationsPricingRepository
     */
    public $longtripAccommodationsPricingsRepository;

    /**
     * @var LongtripAccommodationHotelRepository
     */
    public $longtripAccommodationHotelRepository;
    /**
     * @var LongtripAccommodationTypeRepository
     */
    public $longtripAccommodationTypeRepository;

    /**
     * @var CategoryRepository
     */
    public $categoryRepository;

    /**
     * @var HotelRepository
     */
    public $hotelRepository;

    public function __construct(
        LongtripRouteRepository $longtripRouteRepository,
        LongtripBoardingLocationRepository $longtripBoardingLocationRepository,
        LongtripHotelLabelRepository $longtripHotelLabelRepository,
        InclusionRepository $inclusionRepository,
        ExclusionRepository $exclusionRepository,
        ObservationRepository $observationRepository,
        AdditionalRepository $additionalRepository,
        CountryRepository $countryRepository,
        LongtripAccommodationRepository $longtripAccommodationRepository,
        LongtripAccommodationHotelRepository $longtripAccommodationHotelRepository,
        LongtripAccommodationTypeRepository $longtripAccommodationTypeRepository,
        LongtripAccommodationsPricingRepository $longtripAccommodationsPricingsRepository,
        CategoryRepository $categoryRepository,
        HotelRepository $hotelRepository
    )
    {
        $this->longtripRouteRepository              = $longtripRouteRepository;
        $this->longtripBoardingLocationRepository   = $longtripBoardingLocationRepository;
        $this->longtripHotelLabelRepository         = $longtripHotelLabelRepository;
        $this->inclusionRepository = $inclusionRepository;
        $this->exclusionRepository = $exclusionRepository;
        $this->observationRepository = $observationRepository;
        $this->additionalRepository = $additionalRepository;
        $this->countryRepository = $countryRepository;
        $this->longtripAccommodationRepository = $longtripAccommodationRepository;
        $this->longtripAccommodationHotelRepository = $longtripAccommodationHotelRepository;
        $this->longtripAccommodationTypeRepository = $longtripAccommodationTypeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->hotelRepository = $hotelRepository;
        $this->longtripAccommodationsPricingsRepository = $longtripAccommodationsPricingsRepository;
    }

    /**
     * [createRoute description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function createRoute(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            return view('backend.offers.types.longtrip.routes.create', compact('provider', 'company', 'offer'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.index', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * [storeRoute description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function storeRoute(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            $attributes = $request->toArray();
            
            $longtripRoute = $this->longtripRouteRepository->setOffer($offer)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withSuccess(__('resources.longtrip-routes.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.createRoute', [$provider, $company, $offer])->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * [editRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   LongtripRoute  $longtripRoute  [$longtripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function editRoute(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute)
    {
        try {
            $inclusions             = $this->inclusionRepository->setType(OfferType::LONGTRIP)->list();
            $exclusions             = $this->exclusionRepository->setType(OfferType::LONGTRIP)->list();
            $observations           = $this->observationRepository->setType(OfferType::LONGTRIP)->list();
            $longtripHotelLabels    = $this->longtripHotelLabelRepository->list();
            $types                  = $this->longtripAccommodationTypeRepository->list();
            $hotels                 = $this->hotelRepository
                ->getOfferLongtripType()
                ->getHotelsByProvider([$provider->id])
                ->list();
            $navigation         = $request->get('navigation', '');

            return view('backend.offers.types.longtrip.routes.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('types', $types)
                ->with('hotels', $hotels)
                ->with('inclusions', $inclusions)
                ->with('exclusions', $exclusions)
                ->with('navigation', $navigation)
                ->with('observations', $observations)
                ->with('longtripRoute', $longtripRoute)
                ->with('longtripHotelLabels', $longtripHotelLabels)
                ;
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.index', [$provider, $company])->withError($ex->getMessage());
        }
    }

    /**
     * [updateRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   LongtripRoute  $longtripRoute  [$longtripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function updateRoute(LongTripRouteUpdateRequest $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute)
    {
        try {
            $attributes = $request->toArray();

            $longtripRoute = $this->longtripRouteRepository->update($longtripRoute, $attributes);

            $longtripAccommodation = $this->longtripAccommodationHotelRepository->setOffer($offer)->setLongtripRoute($longtripRoute)->updateQuickAttributes($attributes);
            $navigation         = $request->get('navigation', 'sales-info');


            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute, 'navigation' => $navigation])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute, 'navigation' => $navigation])->withError($ex->getMessage());
        }
    }

    public function destroyRoute(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute)
    {
        try {
            $longtripRoute = $this->longtripRouteRepository->delete($longtripRoute);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('resources.longtrip-routes.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [createRoute description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function createBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute)
    {
        try {
            $countries = $this->countryRepository->list();
            $additionals = $this->additionalRepository->setProvider($provider)->setType($offer->type)->list();

            return view('backend.offers.types.longtrip.routes.boarding.create')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('longtripRoute', $longtripRoute)
                ->with('additionals', $additionals)
                ->with('countries', $countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [storeRoute description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function storeBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute)
    {
        try {
            $attributes = $request->toArray();

            $attributes['boarding_at'] = convertDatetime($attributes['boarding_at']);
            $attributes['ends_at'] = convertDatetime($attributes['ends_at']);

            $longtripBoardingLocation = $this->longtripBoardingLocationRepository->setLongtripRoute($longtripRoute)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.longtrip.editBoardingLocation', [$provider, $company, $offer, $longtripRoute, $longtripBoardingLocation])->withSuccess(__('resources.longtrip-routes-boarding.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.createBoardingLocation', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [editRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   LongtripRoute  $longtripRoute  [$longtripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function editBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripBoardingLocation $longtripBoardingLocation)
    {
        try {
            $countries = $this->countryRepository->list();
            $additionals = $this->additionalRepository->setProvider($provider)->setType($offer->type)->list();

            return view('backend.offers.types.longtrip.routes.boarding.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('longtripRoute', $longtripRoute)
                ->with('longtripBoardingLocation', $longtripBoardingLocation)
                ->with('additionals', $additionals)
                ->with('countries', $countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [updateRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   LongtripRoute  $longtripRoute  [$longtripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function updateBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripBoardingLocation $longtripBoardingLocation)
    {
        try {
            $attributes = $request->toArray();

            $attributes['boarding_at'] = convertDatetime($attributes['boarding_at']);
            $attributes['ends_at'] = convertDatetime($attributes['ends_at']);

            $longtripBoardingLocation = $this->longtripBoardingLocationRepository->update($longtripBoardingLocation, $attributes);

            return redirect()->route('backend.providers.companies.offers.longtrip.editBoardingLocation', [$provider, $company, $offer, $longtripRoute, $longtripBoardingLocation])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editBoardingLocation', [$provider, $company, $offer, $longtripRoute, $longtripBoardingLocation])->withError($ex->getMessage());
        }
    }

    /**
     * [destroyBoardingLocation description]
     *
     * @param   Request                   $request                   [$request description]
     * @param   Provider                  $provider                  [$provider description]
     * @param   Company                   $company                   [$company description]
     * @param   Offer                     $offer                     [$offer description]
     * @param   LongtripRoute             $longtripRoute             [$longtripRoute description]
     * @param   LongtripBoardingLocation  $longtripBoardingLocation  [$longtripBoardingLocation description]
     *
     * @return  [type]                                               [return description]
     */
    public function destroyBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripBoardingLocation $longtripBoardingLocation)
    {
        try {
            $longtripBoardingLocation = $this->longtripBoardingLocationRepository->delete($longtripBoardingLocation);

            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withSuccess(__('resources.longtrip-routes-boarding.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }


    /**
     * [createRoute description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function createLongtripAccommodationHotel(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripAccommodation $longtripAccommodation)
    {
        try {
            $types              = $this->longtripAccommodationTypeRepository->list();
            $countries          = $this->countryRepository->list();
            $hotelCategories    = $this->categoryRepository->listHotel();

            $hotels         = $this->hotelRepository
                ->getOfferLongtripType()
                ->getHotelsByProvider([$provider->id])
                ->list();


            $longtripAccommodation = $this->longtripAccommodationHotelRepository
            ->setOffer($offer)
            ->setLongtripRoute($longtripRoute)
            ->store(['longtrip_accommodation_id' => $longtripAccommodation->id]);

            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withSuccess(__('resources.longtrip-accommodations.created'));

            return view('backend.offers.types.longtrip.routes.accommodations.create')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('types', $types)
                ->with('countries', $countries)
                ->with('hotelCategories', $hotelCategories)
                ->with('hotels', $hotels)
                ->with('longtripRoute',$longtripRoute)
                ->with('longtripAccommodation', $longtripAccommodation)
                ->with('offer', $offer);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [storeRoute description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function storeLongtripAccommodationHotel(LongTripAccommodationHotelRequest $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute)
    {
        $attributes = $request->toArray();
        try {
            // Rule 090921
            throw new Exception('Route not found');

            if (isset($attributes['checkin'])) {
                $attributes['checkin'] = convertDate($attributes['checkin']);
            }

            if (isset($attributes['checkout'])) {
                $attributes['checkout'] = convertDate($attributes['checkout']);
            }

            $attributes['hotel']['provider_id'] = $provider->id;

            $longtripAccommodation = $this->longtripAccommodationHotelRepository->setOffer($offer)->setLongtripRoute($longtripRoute)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withSuccess(__('resources.longtrip-accommodations.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.createLongtripAccommodationHotel', [$provider, $company, $offer, $longtripRoute, $attributes['longtrip_accommodation_id']])->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * [editLongtripAccommodation description]
     *
     * @param   Request                $request                [$request description]
     * @param   Provider               $provider               [$provider description]
     * @param   Company                $company                [$company description]
     * @param   Offer                  $offer                  [$offer description]
     * @param   LongtripRoute          $longtripRoute          [$longtripRoute description]
     * @param   LongtripAccommodation  $longtripAccommodation  [$longtripAccommodation description]
     *
     * @return  [type]                                         [return description]
     */
    public function editLongtripAccommodationHotel(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripAccommodation $longtripAccommodation, LongtripAccommodationHotel $longtripAccommodationHotel)
    {
        try {
            // Rule 090921
            throw new Exception('Route not found');
            $types              = $this->longtripAccommodationTypeRepository->list();
            $countries          = $this->countryRepository->list();
            $hotelCategories    = $this->categoryRepository->listHotel();
            $hotels         = $this->hotelRepository
                ->getOfferLongtripType()
                ->getHotelsByProvider([$provider->id])
                ->list();

            return view('backend.offers.types.longtrip.routes.accommodations.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('longtripAccommodation', $longtripAccommodation)
                ->with('longtripAccommodationHotel', $longtripAccommodationHotel)
                ->with('countries', $countries)
                ->with('hotelCategories', $hotelCategories)
                ->with('hotels', $hotels)
                ->with('types', $types)
                ->with('offer', $offer)
                ->with('longtripRoute', $longtripRoute);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.editRoute', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [updateLongtripAccommodation description]
     *
     * @param   Request                $request                [$request description]
     * @param   Provider               $provider               [$provider description]
     * @param   Company                $company                [$company description]
     * @param   Offer                  $offer                  [$offer description]
     * @param   LongtripRoute          $longtripRoute          [$longtripRoute description]
     * @param   LongtripAccommodation  $longtripAccommodation  [$longtripAccommodation description]
     *
     * @return  [type]                                         [return description]
     */
    public function updateLongtripAccommodationHotel(LongTripAccommodationHotelRequest $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripAccommodation $longtripAccommodation, LongtripAccommodationHotel $longtripAccommodationHotel)
    {
        try {
            // Rule 090921
            throw new Exception('Route not found');
            $attributes = $request->toArray();

            if (isset($attributes['checkin'])) {
                $attributes['checkin'] = convertDate($attributes['checkin']);
            }

            if (isset($attributes['checkout'])) {
                $attributes['checkout'] = convertDate($attributes['checkout']);
            }
            $longtripAccommodationHotel = $this->longtripAccommodationHotelRepository->update($longtripAccommodationHotel, $attributes);

            return redirect()->route('backend.providers.companies.offers.longtrip.editLongtripAccommodationHotel', [$provider, $company, $offer, $longtripRoute, $longtripAccommodation, $longtripAccommodationHotel])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editLongtripAccommodationHotel', [$provider, $company, $offer, $longtripAccommodation, $longtripRoute, $longtripAccommodation, $longtripAccommodationHotel])->withError($ex->getMessage());
        }
    }

    /**
     * [destroyLongtripAccommodation description]
     *
     * @param   Request                $request                [$request description]
     * @param   Provider               $provider               [$provider description]
     * @param   Company                $company                [$company description]
     * @param   Offer                  $offer                  [$offer description]
     * @param   LongtripRoute          $longtripRoute          [$longtripRoute description]
     * @param   LongtripAccommodation  $longtripAccommodation  [$longtripAccommodation description]
     *
     * @return  [type]                                         [return description]
     */
    public function destroyLongtripAccommodationHotel(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripAccommodation $longtripAccommodation, LongtripAccommodationHotel $longtripAccommodationHotel)
    {
        try {
            $longtripAccommodation = $this->longtripAccommodationHotelRepository->delete($longtripAccommodationHotel);

            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withSuccess(__('resources.longtrip-accommodations.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [storeLongtripAccommodation description]
     *
     * @param   Request   $request   [$request description]
     * @param   Provider  $provider  [$provider description]
     * @param   Company   $company   [$company description]
     * @param   Offer     $offer     [$offer description]
     *
     * @return  [type]               [return description]
     */
    public function storeLongtripAccommodation(LongTripAccommodationRequest $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute)
    {
        try {
            $attributes = $request->validated();

            $entity     = $this->longtripAccommodationRepository->setOffer($offer)->setLongtripRoute($longtripRoute)->storeFirstOrCreate($attributes);

            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withSuccess(__('resources.longtrip-accommodations.store'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [destroyLongtripAccommodation description]
     *
     * @param   Request                $request                [$request description]
     * @param   Provider               $provider               [$provider description]
     * @param   Company                $company                [$company description]
     * @param   Offer                  $offer                  [$offer description]
     * @param   LongtripRoute          $longtripRoute          [$longtripRoute description]
     * @param   LongtripAccommodation  $longtripAccommodation  [$longtripAccommodation description]
     *
     * @return  [type]                                         [return description]
     */
    public function destroyLongtripAccommodation(Request $request, Provider $provider, Company $company, Offer $offer, LongtripRoute $longtripRoute, LongtripAccommodation $longtripAccommodation)
    {
        try {
            $longtripRouteId                = $longtripAccommodation->longtrip_route_id;
            $longtripAccommodationTypeId    = $longtripAccommodation->longtrip_accommodation_type_id;
            $longtripAccommodation          = $this->longtripAccommodationRepository->delete($longtripAccommodation);
            $this->longtripAccommodationsPricingsRepository->deleteRelation($longtripRouteId, $longtripAccommodationTypeId);

            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withSuccess(__('resources.longtrip-accommodations.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.longtrip.editRoute', [$provider, $company, $offer, $longtripRoute])->withError($ex->getMessage());
        }
    }

}
