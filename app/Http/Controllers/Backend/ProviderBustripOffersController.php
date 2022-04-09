<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OfferType;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Provider;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\Offers\BustripRouteBoardingLocationStoreRequest;
use App\Http\Requests\Backend\Offers\BustripRouteBoardingLocationUpdateRequest;
use App\Models\BustripBoardingLocation;
use App\Models\BustripRoute;
use App\Repositories\AdditionalRepository;
use App\Repositories\BustripBoardingLocationRepository;
use App\Repositories\BustripRouteRepository;
use App\Repositories\CountryRepository;
use App\Repositories\ExclusionRepository;
use App\Repositories\InclusionRepository;
use App\Repositories\ObservationRepository;
use Exception;
use Illuminate\Http\Request;

class ProviderBustripOffersController extends Controller
{
    /**
     * @var BustripRouteRepository
     */
    public $bustripRouteRepository;

    /**
     * @var BustripBoardingLocationRepository
     */
    public $bustripBoardingLocationRepository;

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

    public function __construct(
        BustripRouteRepository $bustripRouteRepository,
        BustripBoardingLocationRepository $bustripBoardingLocationRepository,
        InclusionRepository $inclusionRepository,
        ExclusionRepository $exclusionRepository,
        ObservationRepository $observationRepository,
        AdditionalRepository $additionalRepository,
        CountryRepository $countryRepository)
    {
        $this->bustripRouteRepository = $bustripRouteRepository;
        $this->bustripBoardingLocationRepository = $bustripBoardingLocationRepository;
        $this->inclusionRepository = $inclusionRepository;
        $this->exclusionRepository = $exclusionRepository;
        $this->observationRepository = $observationRepository;
        $this->additionalRepository = $additionalRepository;
        $this->countryRepository = $countryRepository;
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
            return view('backend.offers.types.bus-trip.routes.create', compact('provider', 'company', 'offer'));
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

            $bustripRoute = $this->bustripRouteRepository->setOffer($offer)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute])->withSuccess(__('resources.bustrip-routes.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.bustrip.createRoute', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [editRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   BustripRoute  $bustripRoute  [$bustripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function editRoute(Request $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute)
    {
        try {
            $inclusions         = $this->inclusionRepository->setType(OfferType::BUSTRIP)->list();
            $exclusions         = $this->exclusionRepository->setType(OfferType::BUSTRIP)->list();
            $observations       = $this->observationRepository->setType(OfferType::BUSTRIP)->list();
            $navigation         = $request->get('navigation', '');

            return view('backend.offers.types.bus-trip.routes.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('inclusions', $inclusions)
                ->with('exclusions', $exclusions)
                ->with('navigation', $navigation)
                ->with('observations', $observations)
                ->with('bustripRoute', $bustripRoute);
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
     * @param   BustripRoute  $bustripRoute  [$bustripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function updateRoute(Request $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute)
    {
        try {
            $attributes = $request->toArray();

            $bustripRoute = $this->bustripRouteRepository->update($bustripRoute, $attributes);
            $navigation         = $request->get('navigation', 'sales-info');

            return redirect()->route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute, 'navigation' => $navigation])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute, 'navigation' => $navigation])->withError($ex->getMessage());
        }
    }

    public function destroyRoute(Request $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute)
    {
        try {
            $bustripRoute = $this->bustripRouteRepository->delete($bustripRoute);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('resources.bustrip-routes.deleted'));
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
    public function createBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute)
    {
        try {
            $countries = $this->countryRepository->list();
            $additionals = $this->additionalRepository->setProvider($provider)->setType($offer->type)->list();

            return view('backend.offers.types.bus-trip.routes.boarding.create')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('bustripRoute', $bustripRoute)
                ->with('additionals', $additionals)
                ->with('countries', $countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute])->withError($ex->getMessage());
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
    public function storeBoardingLocation(BustripRouteBoardingLocationStoreRequest $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute)
    {
        try {
            $attributes = $request->toArray();

            $attributes['boarding_at'] = convertDatetime($attributes['boarding_at']);

            $bustripBoardingLocation = $this->bustripBoardingLocationRepository->setBustripRoute($bustripRoute)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.bustrip.editBoardingLocation', [$provider, $company, $offer, $bustripRoute, $bustripBoardingLocation])->withSuccess(__('resources.bustrip-routes-boarding.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.bustrip.createBoardingLocation', [$provider, $company, $offer, $bustripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [editRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   BustripRoute  $bustripRoute  [$bustripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function editBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute, BustripBoardingLocation $bustripBoardingLocation)
    {
        try {
            $countries = $this->countryRepository->list();
            $additionals = $this->additionalRepository->setProvider($provider)->setType($offer->type)->list();

            return view('backend.offers.types.bus-trip.routes.boarding.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('bustripRoute', $bustripRoute)
                ->with('bustripBoardingLocation', $bustripBoardingLocation)
                ->with('additionals', $additionals)
                ->with('countries', $countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [updateRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   BustripRoute  $bustripRoute  [$bustripRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function updateBoardingLocation(BustripRouteBoardingLocationUpdateRequest $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute, BustripBoardingLocation $bustripBoardingLocation)
    {
        try {
            $attributes = $request->toArray();

            $attributes['boarding_at'] = convertDatetime($attributes['boarding_at']);

            $bustripBoardingLocation = $this->bustripBoardingLocationRepository->update($bustripBoardingLocation, $attributes);

            return redirect()->route('backend.providers.companies.offers.bustrip.editBoardingLocation', [$provider, $company, $offer, $bustripRoute, $bustripBoardingLocation])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.bustrip.editBoardingLocation', [$provider, $company, $offer, $bustripRoute, $bustripBoardingLocation])->withError($ex->getMessage());
        }
    }

    public function destroyBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, BustripRoute $bustripRoute, BustripBoardingLocation $bustripBoardingLocation)
    {
        try {
            $bustripBoardingLocation = $this->bustripBoardingLocationRepository->delete($bustripBoardingLocation);

            return redirect()->route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute])->withSuccess(__('resources.bustrip-routes-boarding.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.bustrip.editRoute', [$provider, $company, $offer, $bustripRoute])->withError($ex->getMessage());
        }
    }
}
