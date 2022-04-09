<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\HotelAccommodationStructure;
use App\Repositories\HotelAccommodationStructureRepository;
use Exception;
use Illuminate\Http\Request;

class HotelAccommodationStructureController extends Controller
{
    /**
     * @var HotelAccommodationStructureRepository
     */
    protected $repository;

    public function __construct(HotelAccommodationStructureRepository $repository)
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

        $hotelAccommodationStructures = $this->repository->list();

        return view('backend.configs.providers.hotel.accommodation-structure.index')
            ->with('hotelAccommodationStructures', $hotelAccommodationStructures);
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
            return view('backend.configs.providers.hotel.accommodation-structure.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  HotelAccommodationStructureRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $hotelAccommodationStructure = $this->repository->store($attributes);

            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.edit', $hotelAccommodationStructure)->withSuccess(__('resources.categories.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  HotelAccommodationStructure  $hotelAccommodationStructure
     * @return \Illuminate\Http\Response
     */
    public function edit(HotelAccommodationStructure $hotelAccommodationStructure)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            return view('backend.configs.providers.hotel.accommodation-structure.edit')
            ->with('hotelAccommodationStructure', $hotelAccommodationStructure);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  HotelAccommodationStructure  $hotelAccommodationStructure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotelAccommodationStructure $hotelAccommodationStructure)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $hotelAccommodationStructure = $this->repository->update($hotelAccommodationStructure, $attributes);

            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.edit', $hotelAccommodationStructure->id)->withSuccess(__('resources.categories.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.edit', $hotelAccommodationStructure->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  HotelAccommodationStructure  $hotelAccommodationStructure
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelAccommodationStructure $hotelAccommodationStructure)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $hotelAccommodationStructure = $this->repository->delete($hotelAccommodationStructure);

            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.index')->withSuccess(__('resources.categories.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.accommodation-structure.index')->withError($ex->getMessage());
        }
    }
}
