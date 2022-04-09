<?php

namespace App\Http\Controllers\Backend;

use App\Enums\OfferType;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\HotelAccommodation;
use App\Models\Offer;
use App\Models\Provider;
use App\Repositories\ExclusionRepository;
use App\Repositories\HotelAccommodationRepository;
use App\Repositories\HotelAccommodationStructureRepository;
use App\Repositories\HotelAccommodationTypeRepository;
use App\Repositories\InclusionRepository;
use Exception;
use Illuminate\Http\Request;

class ProviderHotelOffersController extends Controller
{
    /**
     * @var HotelAccommodationRepository
     */
    public $hotelAccommodationRepository;

    /**
     * @var HotelAccommodationTypeRepository
     */
    public $hotelAccommodationTypeRepository;

    /**
     * @var HotelAccommodationStructureRepository
     */
    public $hotelAccommodationStructure;

    /**
     * @var InclusionRepository
     */
    public $inclusionRepository;

    /**
     * @var ExclusionRepository
     */
    public $exclusionRepository;

    public function __construct(
        HotelAccommodationRepository $hotelAccommodationRepository,
        HotelAccommodationTypeRepository $hotelAccommodationTypeRepository,
        HotelAccommodationStructureRepository $hotelAccommodationStructure,
        InclusionRepository $inclusionRepository,
        ExclusionRepository $exclusionRepository
    ) {
        $this->hotelAccommodationRepository = $hotelAccommodationRepository;
        $this->hotelAccommodationTypeRepository = $hotelAccommodationTypeRepository;
        $this->hotelAccommodationStructure = $hotelAccommodationStructure;
        $this->inclusionRepository = $inclusionRepository;
        $this->exclusionRepository = $exclusionRepository;
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
    public function createHotelAccommodation(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            $types = $this->hotelAccommodationTypeRepository->list();
            $hotelAccommodationStructures = $this->hotelAccommodationStructure->list();
            $inclusions = $this->inclusionRepository->setType(OfferType::HOTEL)->list();
            $exclusions = $this->exclusionRepository->setType(OfferType::HOTEL)->list();

            return view('backend.offers.types.hotel.accommodations.create')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('types', $types)
                ->with('hotelAccommodationStructures', $hotelAccommodationStructures)
                ->with('inclusions', $inclusions)
                ->with('exclusions', $exclusions)
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
    public function storeHotelAccommodation(Request $request, Provider $provider, Company $company, Offer $offer)
    {
        try {
            $attributes = $request->toArray();

            $hotelAccommodation = $this->hotelAccommodationRepository->setOffer($offer)->store($attributes);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('resources.hotel-accommodations.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.hotel.createHotelAccommodation', [$provider, $company, $offer])->withError($ex->getMessage())->withInput();
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
    public function editHotelAccommodation(Request $request, Provider $provider, Company $company, Offer $offer, HotelAccommodation $hotelAccommodation)
    {
        try {
            $types = $this->hotelAccommodationTypeRepository->list();
            $hotelAccommodationStructures = $this->hotelAccommodationStructure->list();
            $inclusions = $this->inclusionRepository->setType(OfferType::HOTEL)->list();
            $exclusions = $this->exclusionRepository->setType(OfferType::HOTEL)->list();

            return view('backend.offers.types.hotel.accommodations.edit')
                ->with('provider', $provider)
                ->with('company', $company)
                ->with('hotelAccommodation', $hotelAccommodation)
                ->with('types', $types)
                ->with('hotelAccommodationStructures', $hotelAccommodationStructures)
                ->with('inclusions', $inclusions)
                ->with('exclusions', $exclusions)
                ->with('offer', $offer);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withError($ex->getMessage());
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
    public function updateHotelAccommodation(Request $request, Provider $provider, Company $company, Offer $offer, HotelAccommodation $hotelAccommodation)
    {
        try {
            $attributes = $request->toArray();

            $hotelAccommodation = $this->hotelAccommodationRepository->update($hotelAccommodation, $attributes);

            return redirect()->route('backend.providers.companies.offers.hotel.editHotelAccommodation', [$provider, $company, $offer, $hotelAccommodation])->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.hotel.editHotelAccommodation', [$provider, $company, $offer, $hotelAccommodation])->withError($ex->getMessage());
        }
    }

    /**
     * [destroyHotelAccommodation description]
     *
     * @param   Request             $request             [$request description]
     * @param   Provider            $provider            [$provider description]
     * @param   Company             $company             [$company description]
     * @param   Offer               $offer               [$offer description]
     * @param   HotelAccommodation  $hotelAccommodation  [$hotelAccommodation description]
     *
     * @return  [type]                                   [return description]
     */
    public function destroyHotelAccommodation(Request $request, Provider $provider, Company $company, Offer $offer, HotelAccommodation $hotelAccommodation)
    {
        try {
            $hotelAccommodation = $this->hotelAccommodationRepository->delete($hotelAccommodation);

            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer])->withSuccess(__('resources.hotel-accommodations.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.edit', [$provider, $company, $offer, $hotelAccommodation])->withError($ex->getMessage());
        }
    }

    /**
     * [destroyGalleryImage description]
     *
     * @param   Provider            $provider            [$provider description]
     * @param   Company             $company             [$company description]
     * @param   Offer               $offer               [$offer description]
     * @param   HotelAccommodation  $hotelAccommodation  [$hotelAccommodation description]
     * @param   string              $image               [$image description]
     *
     * @return  [type]                                   [return description]
     */
    public function destroyGalleryImage(Provider $provider, Company $company, Offer $offer, HotelAccommodation $hotelAccommodation, string $image)
    {
        try {
            $this->hotelAccommodationRepository->deleteImage($hotelAccommodation, $image);

            return redirect()->route('backend.providers.companies.offers.hotel.editHotelAccommodation', [$provider, $company, $offer, $hotelAccommodation])->withSuccess(__('resources.images.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.companies.offers.hotel.editHotelAccommodation', [$provider, $company, $offer, $hotelAccommodation])->withError($ex->getMessage());
        }
    }
}
