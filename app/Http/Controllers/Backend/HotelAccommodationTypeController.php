<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HotelAccommodationType;
use App\Repositories\HotelAccommodationTypeRepository;
use Exception;
use Illuminate\Http\Request;

class HotelAccommodationTypeController extends Controller
{
    /**
     * @var HotelAccommodationTypeRepository
     */
    protected $repository;

    public function __construct(HotelAccommodationTypeRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        $hotelAccommodationTypes = $this->repository->list();

        return view('backend.configs.providers.hotel.accommodation-types.index')
            ->with('hotelAccommodationTypes', $hotelAccommodationTypes);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }
        
        try {
            return view('backend.configs.providers.hotel.accommodation-types.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-types.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  HotelAccommodationTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $hotelAccommodationType = $this->repository->store($attributes);

            return redirect()->route('backend.configs.providers.hotel.accommodation-types.edit', $hotelAccommodationType)->withSuccess(__('resources.categories.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-types.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  HotelAccommodationType  $hotelAccommodationType
     * @return \Illuminate\Http\Response
     */
    public function edit(HotelAccommodationType $hotelAccommodationType)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            return view('backend.configs.providers.hotel.accommodation-types.edit')
                ->with('hotelAccommodationType', $hotelAccommodationType);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-types.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  HotelAccommodationType  $hotelAccommodationType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotelAccommodationType $hotelAccommodationType)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $hotelAccommodationType = $this->repository->update($hotelAccommodationType, $attributes);

            return redirect()->route('backend.configs.providers.hotel.accommodation-types.edit', $hotelAccommodationType->id)->withSuccess(__('resources.categories.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-types.edit', $hotelAccommodationType->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  HotelAccommodationType  $hotelAccommodationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelAccommodationType $hotelAccommodationType)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $hotelAccommodationType = $this->repository->delete($hotelAccommodationType);

            return redirect()->route('backend.configs.providers.hotel.accommodation-types.index')->withSuccess(__('resources.categories.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-types.index')->withError($ex->getMessage());
        }
    }
}
