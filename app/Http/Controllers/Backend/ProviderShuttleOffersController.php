<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OfferType;
use App\Models\Company;
use App\Models\Offer;
use App\Models\Provider;
use App\Http\Controllers\Controller;
use App\Models\ShuttleBoardingLocation;
use App\Models\ShuttleRoute;
use App\Repositories\AdditionalRepository;
use App\Repositories\ShuttleBoardingLocationRepository;
use App\Repositories\ShuttleRouteRepository;
use App\Repositories\CountryRepository;
use App\Repositories\ExclusionRepository;
use App\Repositories\InclusionRepository;
use App\Repositories\ObservationRepository;
use Exception;
use Illuminate\Http\Request;

class ProviderShuttleOffersController extends Controller
{
    /**
     * @var ShuttleRouteRepository
     */
    public $shuttleRouteRepository;

    /**
     * @var ShuttleBoardingLocationRepository
     */
    public $shuttleBoardingLocationRepository;

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
        ShuttleRouteRepository $shuttleRouteRepository,
        ShuttleBoardingLocationRepository $shuttleBoardingLocationRepository,
        InclusionRepository $inclusionRepository,
        ExclusionRepository $exclusionRepository,
        ObservationRepository $observationRepository,
        AdditionalRepository $additionalRepository,
        CountryRepository $countryRepository)
    {
        $this->shuttleRouteRepository = $shuttleRouteRepository;
        $this->shuttleBoardingLocationRepository = $shuttleBoardingLocationRepository;
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
            return view('backend.offers.types.shuttle.routes.create', compact('provider', 'company', 'offer'));
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

            $shuttleRoute = $this->shuttleRouteRepository->setOffer($offer)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $shuttleRoute])->withSuccess(__('resources.shuttle-routes.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.shuttle.createRoute', [$provider, $company, $offer])->withError($ex->getMessage());
        }
    }

    /**
     * [editRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   ShuttleRoute  $shuttleRoute  [$shuttleRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function editRoute(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute)
    {
        try {
            $inclusions = $this->inclusionRepository->setType(OfferType::SHUTTLE)->list();
            $exclusions = $this->exclusionRepository->setType(OfferType::SHUTTLE)->list();
            $observations = $this->observationRepository->setType(OfferType::SHUTTLE)->list();
            $navigation         = $request->get('navigation', '');

            return view('backend.offers.types.shuttle.routes.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('inclusions', $inclusions)
                ->with('exclusions', $exclusions)
                ->with('navigation', $navigation)
                ->with('observations', $observations)
                ->with('shuttleRoute', $shuttleRoute);
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
     * @param   ShuttleRoute  $shuttleRoute  [$shuttleRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function updateRoute(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute)
    {
        try {
            $attributes = $request->toArray();

            $shuttleRoute = $this->shuttleRouteRepository->update($shuttleRoute, $attributes);
            $navigation         = $request->get('navigation', 'sales-info');

            return redirect()->route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $shuttleRoute, 'navigation' => $navigation])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $shuttleRoute, 'navigation' => $navigation])->withError($ex->getMessage());
        }
    }

    public function destroyRoute(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute)
    {
        try {
            $shuttleRoute = $this->shuttleRouteRepository->delete($shuttleRoute);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('resources.shuttle-routes.deleted'));
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
    public function createBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute)
    {
        try {
            $countries = $this->countryRepository->list();
            $additionals = $this->additionalRepository->setProvider($provider)->setType($offer->type)->list();

            return view('backend.offers.types.shuttle.routes.boarding.create')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('shuttleRoute', $shuttleRoute)
                ->with('additionals', $additionals)
                ->with('countries', $countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $shuttleRoute])->withError($ex->getMessage());
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
    public function storeBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute)
    {
        try {
            $attributes = $request->toArray();

            $attributes['boarding_at'] = convertDatetime($attributes['boarding_at']);

            $shuttleBoardingLocation = $this->shuttleBoardingLocationRepository->setShuttleRoute($shuttleRoute)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.shuttle.editBoardingLocation', [$provider, $company, $offer, $shuttleRoute, $shuttleBoardingLocation])->withSuccess(__('resources.shuttle-routes-boarding.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.shuttle.createBoardingLocation', [$provider, $company, $offer, $shuttleRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [editRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   ShuttleRoute  $shuttleRoute  [$shuttleRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function editBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute, ShuttleBoardingLocation $shuttleBoardingLocation)
    {
        try {
            $countries = $this->countryRepository->list();
            $additionals = $this->additionalRepository->setProvider($provider)->setType($offer->type)->list();

            return view('backend.offers.types.shuttle.routes.boarding.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('offer', $offer)
                ->with('shuttleRoute', $shuttleRoute)
                ->with('shuttleBoardingLocation', $shuttleBoardingLocation)
                ->with('additionals', $additionals)
                ->with('countries', $countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $shuttleRoute])->withError($ex->getMessage());
        }
    }

    /**
     * [updateRoute description]
     *
     * @param   Request       $request       [$request description]
     * @param   Provider      $provider      [$provider description]
     * @param   Company       $company       [$company description]
     * @param   Offer         $offer         [$offer description]
     * @param   ShuttleRoute  $shuttleRoute  [$shuttleRoute description]
     *
     * @return  [type]                       [return description]
     */
    public function updateBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute, ShuttleBoardingLocation $shuttleBoardingLocation)
    {
        try {
            $attributes = $request->toArray();

            $attributes['boarding_at'] = convertDatetime($attributes['boarding_at']);

            $shuttleBoardingLocation = $this->shuttleBoardingLocationRepository->update($shuttleBoardingLocation, $attributes);

            return redirect()->route('backend.providers.companies.offers.shuttle.editBoardingLocation', [$provider, $company, $offer, $shuttleRoute, $shuttleBoardingLocation])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.shuttle.editBoardingLocation', [$provider, $company, $offer, $shuttleRoute, $shuttleBoardingLocation])->withError($ex->getMessage());
        }
    }

    public function destroyBoardingLocation(Request $request, Provider $provider, Company $company, Offer $offer, ShuttleRoute $shuttleRoute, ShuttleBoardingLocation $shuttleBoardingLocation)
    {
        try {
            $shuttleBoardingLocation = $this->shuttleBoardingLocationRepository->delete($shuttleBoardingLocation);

            return redirect()->route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $shuttleRoute])->withSuccess(__('resources.shuttle-routes-boarding.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.shuttle.editRoute', [$provider, $company, $offer, $shuttleRoute])->withError($ex->getMessage());
        }
    }
}
