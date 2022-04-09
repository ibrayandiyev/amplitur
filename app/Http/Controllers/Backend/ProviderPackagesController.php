<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Package;
use App\Models\Provider;
use App\Repositories\CountryRepository;
use App\Repositories\PackageRepository;
use Exception;
use Illuminate\Http\Request;

class ProviderPackagesController extends Controller
{
    /**
     * @var PackageRepository
     */
    protected $repository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    public function __construct(PackageRepository $repository, CountryRepository $countryRepository)
    {
        $this->repository = $repository;
        $this->countryRepository = $countryRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $packages = $this->repository->list();

            return view('backend.packages.index', compact('packages'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, Provider $provider, Event $event)
    {
        try {
            $countries = $this->countryRepository->list();

            return view('backend.packages.create', compact('countries'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.create')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $attributes = $request->toArray();

            $package = $this->repository->store($attributes);

            return redirect()->route('backend.packages.edit', $package)->withSuccess(__('resources.packages.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function edit(Package $package)
    {
        try {
            $address = $package->address;
            $countries = $this->countryRepository->list();

            return view('backend.providers.edit', compact('provider', 'countries', 'address'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Package $package)
    {
        try {
            $attributes = $request->toArray();

            $package = $this->repository->update($package, $attributes);

            return redirect()->route('backend.packages.edit', $package->id)->withSuccess(__('messages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.edit', $package->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Package  $package
     * @return \Illuminate\Http\Response
     */
    public function destroy(Package $package)
    {
        try {
            $package = $this->repository->delete($package);

            return redirect()->route('backend.packages.index')->withSuccess(__('resources.packages.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.packages.index')->withError($ex->getMessage());
        }
    }
}
