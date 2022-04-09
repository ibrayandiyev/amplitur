<?php

namespace App\Http\Controllers\Backend;

use App\Enums\Hotel as EnumsHotel;
use App\Enums\OfferType;
use App\Enums\ProcessStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\HotelRequest;
use App\Models\Hotel;
use App\Repositories\CategoryRepository;
use App\Repositories\HotelRepository;
use App\Repositories\CountryRepository;
use App\Repositories\HotelStructureRepository;
use App\Repositories\ObservationRepository;
use App\Repositories\ProviderRepository;
use App\Repositories\StateRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HotelsController extends Controller
{
    /**
     * @var HotelRepository
     */
    protected $repository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var HotelStructureRepository
     */
    protected $hotelStructureRepository;

    /**
     * @var ProviderRepository
     */
    protected $providerRepository;

    /**
     * @var ObservationRepository
     */
    protected $observationRepository;

    /**
     * @var StateRepository
     */
    protected $stateRepository;

    public function __construct(HotelRepository $repository, 
        CountryRepository $countryRepository, 
        StateRepository $stateRepository, 
        CategoryRepository $categoryRepository,
        ObservationRepository $observationRepository,
        HotelStructureRepository $hotelStructureRepository,
        ProviderRepository $providerRepository
        )
    {
        $this->repository = $repository;
        $this->categoryRepository = $categoryRepository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->categoryRepository = $categoryRepository;
        $this->observationRepository = $observationRepository;
        $this->hotelStructureRepository = $hotelStructureRepository;
        $this->providerRepository = $providerRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize('view', Hotel::class);
        $_params    = $request->all();
        if(isset($_params['lt_referer'])){
            if($_params['lt_referer']  == 0 ){
                session()->put("lt_referer", null);
            }else{
                session()->put("lt_referer", $_params['lt_referer']);
            }
        }
        try {
            return view('backend.hotels.index');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * [jsonFilter description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function jsonFilter(Request $request)
    {
        try {
            $registryType = OfferType::LONGTRIP;
            $hotels = $this->repository->filter([
                'name' => $request->input('q'),
                'registry_type' => $registryType
            ], 100);

            return response()->json($hotels);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);

            return response()->json([]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', Hotel::class);

        try {
            $countries          = $this->countryRepository->list();
            $states             = $this->stateRepository->getByCountryCode('BR');
            $categories         = $this->categoryRepository->listEvent();
            $hotelCategories    = $this->categoryRepository->listHotel();
            $hotelStructures    = $this->hotelStructureRepository->list();
            $observations       = $this->observationRepository->setType(OfferType::HOTEL)->list();
            $providers          = $this->providerRepository->list();


            return view('backend.hotels.create', compact('countries', 
                'states',  
                'categories', 
                'hotelCategories',
                'hotelStructures',
                'observations',
                'providers'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.hotels.create')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  HotelRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HotelRequest $request)
    {
        $this->authorize('manage', Hotel::class);
        
        try {
            $attributes = $request->toArray();
            $provider   = (auth('providers')->user() != null)?auth('providers')->user()->id:(isset($attributes['hotel']['provider_id'])?$attributes['hotel']['provider_id']:null);
            $attributes['provider_id']  = $provider;
            $hotel = $this->repository->setProvider($provider)->setStatus(ProcessStatus::IN_ANALYSIS)->store($attributes);

            return redirect()->route('backend.hotels.edit', $hotel)->withSuccess(__('resources.hotels.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.hotels.create')->withError($ex->getMessage())->withInput($attributes);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function edit(Hotel $hotel)
    {
        $this->authorize('update', $hotel);

        try {
            $address            = $hotel->address;
            $countries          = $this->countryRepository->list();
            $states             = $this->stateRepository->getByCountryCode('BR');
            $categories         = $this->categoryRepository->listHotel();
            $hotelCategories    = $this->categoryRepository->listHotel();
            $hotelStructures    = $this->hotelStructureRepository->list();
            $observations       = $this->observationRepository->setType(OfferType::HOTEL)->list();
            $providers          = $this->providerRepository->list();

            if($hotel->registry_type != OfferType::LONGTRIP){
                throw new Exception('Entity not found');
            }

            return view('backend.hotels.edit', compact('hotel', 
                'countries', 
                'states', 'address', 'categories',
                'hotelCategories',
                'hotelStructures',
                'observations',
                'providers'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.hotels.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  HotelRequest $request
     * @param  Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function update(HotelRequest $request, Hotel $hotel)
    {
        $this->authorize('update', $hotel);

        $provider   = (auth('providers')->user() != null)?auth('providers')->user()->id:$hotel->provider_id;

        try {
            if($hotel->registry_type != OfferType::LONGTRIP){
                throw new Exception('Entity not found');
            }
            $attributes                 = $request->toArray();
            $attributes['provider_id']  = $provider;

            $hotel = $this->repository->update($hotel, $attributes);

            return redirect()->route('backend.hotels.edit', $hotel->id)->withSuccess(__('resources.hotels.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.hotels.edit', $hotel->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Hotel  $hotel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Hotel $hotel)
    {
        $this->authorize('delete', $hotel);

        try {
            $hotel = $this->repository->delete($hotel);

            return redirect()->route('backend.hotels.index')->withSuccess(__('resources.hotels.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.hotels.index')->withError($ex->getMessage());
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
        $this->authorize('manage', Hotel::class);

        try {
            $params = $request->all();
            $provider   = (auth('providers')->user() != null)?auth('providers')->user()->id:null;
            if($provider != null){
                $params['provider_id'] = $provider;
            }
            $hotels = $this->repository->filter($params);

            return view('backend.hotels.index', compact('events', 'params'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.hotels.index')->withError($ex->getMessage());
        }
    }

    /**
     * [datatable description]
     *
     * @return  [type]  [return description]
     */
    public function datatable()
    {
        $query      = Hotel::query();
        $provider   = (auth('providers')->user() != null)?auth('providers')->user()->id:null;
        if($provider != null){
            $query = $query->where('provider_id', $provider);
        }
        $registryType = OfferType::LONGTRIP;
        $query = $query->where('registry_type', $registryType);
        $response = datatables()
            ->eloquent($query)
            ->setTransformer(function (Hotel $hotel) {
                $categoryName = '-';
                if($hotel->category){
                    $categoryName = $hotel->category->getTranslation('name', app()->getLocale());
                }
                $name                   = Str::upper($hotel->name);
                $registryType          = EnumsHotel::getOfferTypeTranslation($hotel->registry_type);
                $categoryName           = Str::upper($categoryName);
                $extra_observations     = Str::upper($hotel->extra_observations);
                $providerName           = ($hotel->provider_id !=null)?$hotel->provider->name:"-";
                return [
                    'id' => $hotel->id,
                    'name' => $name,
                    'registry_type' => $registryType,
                    'category_id' => $categoryName,
                    'provider' => $providerName,
                    'checkin' => Str::upper($hotel->checkin),
                    'checkout' => Str::upper($hotel->checkout),
                    'extra_observations' => $extra_observations,
                    'status' => $hotel->statusLabel,
                    'created_at' => $hotel->createdAtLabel,
                    'updated_at' => $hotel->updatedAtLabel,
                ];
            });

        return $response->toJson();
    }

}
