<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Additional;
use App\Models\AdditionalGroup;
use App\Repositories\AdditionalGroupRepository;
use App\Repositories\AdditionalRepository;
use App\Repositories\PackageRepository;
use Exception;
use Illuminate\Http\Request;

class AdditionalController extends Controller
{
    /**
     * @var AdditionalRepository
     */
    protected $repository;

    /**
     * @var AdditionalGroupRepository
     */
    protected $additionalGroupRepository;

    /**
     * @var PackageRepository
     */
    protected $packageRepository;

    public function __construct(
        AdditionalRepository $repository,
        AdditionalGroupRepository $additionalGroupRepository,
        PackageRepository $packageRepository
        )
    {
        $this->repository = $repository;
        $this->additionalGroupRepository = $additionalGroupRepository;
        $this->packageRepository = $packageRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
            $packages = $this->packageRepository->list();
            $additionals = $this->repository->list();

            return view('backend.additionals.index')
                ->with('additionals', $additionals)
                ->with('packages', $packages);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $packages = $this->packageRepository->list();
        $groups = $this->additionalGroupRepository->list();
    
        try {
            return view('backend.additionals.create')
                ->with('packages', $packages)
                ->with('groups', $groups);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.index')->withError($ex->getMessage());
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
            $additional = $this->repository->setProvider(1)->store($attributes);

            return redirect()->route('backend.additionals.edit', $additional)->withSuccess(__('resources.additionals.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.create')->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Additional $additional)
    {
        $packages = $this->packageRepository->list();
        $groups = $this->additionalGroupRepository->list();

        try {
            return view('backend.additionals.edit')
                ->with('groups', $groups)
                ->with('packages', $packages)
                ->with('additional', $additional);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request      $request
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Additional $additional)
    {
        try {
            $attributes = $request->toArray();
            $additional = $this->repository->update($additional, $attributes);

            if ($request->input('redirect') == 'back') {
                return redirect()->route('backend.additionals.index')->withSuccess(__('resources.additionals.updated'));
            }

            return redirect()->route('backend.additionals.edit', $additional)->withSuccess(__('resources.additionals.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.edit', $additional)->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request      $request
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Additional $additional)
    {
        try {
            $additional = $this->repository->delete($additional);

            return redirect()->route('backend.additionals.index')->withSuccess(__('resources.additionals.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.index')->withError($ex->getMessage());
        }
    }
}
