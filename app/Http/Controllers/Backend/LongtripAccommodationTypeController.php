<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\LongtripAccommodationType;
use App\Repositories\LongtripAccommodationTypeRepository;
use Exception;
use Illuminate\Http\Request;

class LongtripAccommodationTypeController extends Controller
{
    /**
     * @var LongtripAccommodationTypeRepository
     */
    protected $repository;

    public function __construct(LongtripAccommodationTypeRepository $repository)
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

        $longtripAccommodationTypes = $this->repository->list();

        return view('backend.configs.providers.longtrip.accommodation-types.index')
            ->with('longtripAccommodationTypes', $longtripAccommodationTypes);
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
            return view('backend.configs.providers.longtrip.accommodation-types.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  LongtripAccommodationTypeRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $longtripAccommodationType = $this->repository->store($attributes);

            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.edit', $longtripAccommodationType)->withSuccess(__('resources.categories.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  LongtripAccommodationType  $longtripAccommodationType
     * @return \Illuminate\Http\Response
     */
    public function edit(LongtripAccommodationType $longtripAccommodationType)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            return view('backend.configs.providers.longtrip.accommodation-types.edit')
                ->with('longtripAccommodationType', $longtripAccommodationType);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  LongtripAccommodationType  $longtripAccommodationType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, LongtripAccommodationType $longtripAccommodationType)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $attributes = $request->toArray();

            $longtripAccommodationType = $this->repository->update($longtripAccommodationType, $attributes);

            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.edit', $longtripAccommodationType->id)->withSuccess(__('resources.categories.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.edit', $longtripAccommodationType->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  LongtripAccommodationType  $longtripAccommodationType
     * @return \Illuminate\Http\Response
     */
    public function destroy(LongtripAccommodationType $longtripAccommodationType)
    {
        if (!user()->canManageProviderDetails()) {
            forbidden();
        }

        try {
            $longtripAccommodationType = $this->repository->delete($longtripAccommodationType);

            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.index')->withSuccess(__('resources.categories.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.configs.providers.longtrip.accommodation-types.index')->withError($ex->getMessage());
        }
    }
}
