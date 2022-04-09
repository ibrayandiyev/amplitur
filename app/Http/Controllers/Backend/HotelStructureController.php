<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\hotelStructure;
use App\Repositories\HotelStructureRepository;
use Exception;
use Illuminate\Http\Request;

class HotelStructureController extends Controller
{
    /**
     * @var HotelStructureRepository
     */
    protected $repository;

    public function __construct(HotelStructureRepository $repository)
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

        $hotelStructures = $this->repository->list();

        return view('backend.configs.providers.hotel.hotel-structure.index')
        ->with('hotelStructures', $hotelStructures);
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
            return view('backend.configs.providers.hotel.hotel-structure.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.hotel-structure.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  hotelStructureRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $hotelStructure = $this->repository->store($attributes);

            return redirect()->route('backend.configs.providers.hotel.hotel-structure.edit', $hotelStructure)->withSuccess(__('resources.categories.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.hotel-structure.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  HotelStructure  $hotelStructure
     * @return \Illuminate\Http\Response
     */
    public function edit(HotelStructure $hotelStructure)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            return view('backend.configs.providers.hotel.hotel-structure.edit')
            ->with('hotelStructure', $hotelStructure);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.hotel-structure.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  HotelStructure  $hotelStructure
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, HotelStructure $hotelStructure)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $hotelStructure = $this->repository->update($hotelStructure, $attributes);

            return redirect()->route('backend.configs.providers.hotel.hotel-structure.edit', $hotelStructure->id)->withSuccess(__('resources.categories.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.hotel-structure.edit', $hotelStructure->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  HotelStructure  $hotelStructure
     * @return \Illuminate\Http\Response
     */
    public function destroy(HotelStructure $hotelStructure)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $hotelStructure = $this->repository->delete($hotelStructure);

            return redirect()->route('backend.configs.providers.hotel.hotel-structure.index')->withSuccess(__('resources.categories.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.hotel.hotel-structure.index')->withError($ex->getMessage());
        }
    }
}
