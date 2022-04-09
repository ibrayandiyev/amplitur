<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Exclusion;
use App\Repositories\ExclusionGroupRepository;
use App\Repositories\ExclusionRepository;
use Exception;
use Illuminate\Http\Request;

class ExclusionController extends Controller
{
    /**
     * @var ExclusionRepository
     */
    protected $repository;

    /**
     * @var ExclusionGroupRepository
     */
    protected $exclusionGroupRepository;

    public function __construct(ExclusionRepository $repository, ExclusionGroupRepository $exclusionGroupRepository)
    {
        $this->repository = $repository;
        $this->exclusionGroupRepository = $exclusionGroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $type)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $exclusions = $this->repository->setType($type)->list();

            return view('backend.exclusions.index')
                ->with('type', $type)
                ->with('exclusions', $exclusions);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, string $type)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $groups = $this->exclusionGroupRepository->setType($type)->list();

            return view('backend.exclusions.create')
                ->with('type', $type)
                ->with('groups', $groups);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.index', ['type' => $type])->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, string $type)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $attributes = $request->toArray();
            $exclusion = $this->repository->store($attributes);

            return redirect()->route('backend.exclusions.edit', ['type' => $type, $exclusion])->withSuccess(__('resources.exclusions.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.create', ['type' => $type])->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Exclusion  $exclusion
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type, Exclusion $exclusion)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $groups = $this->exclusionGroupRepository->setType($type)->list();

            return view('backend.exclusions.edit')
                ->with('type', $type)
                ->with('groups', $groups)
                ->with('exclusion', $exclusion);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Exclusion  $exclusion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, Exclusion $exclusion)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $attributes = $request->toArray();
            $exclusion = $this->repository->update($exclusion, $attributes);

            return redirect()->route('backend.exclusions.edit', ['type' => $type, $exclusion])->withSuccess(__('resources.exclusions.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.edit', ['type' => $type, $exclusion])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request    $request
     * @param  string     $type
     * @param  Exclusion  $exclusion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $type, Exclusion $exclusion)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $exclusion = $this->repository->delete($exclusion);

            return redirect()->route('backend.exclusions.index', ['type' => $type])->withSuccess(__('resources.exclusions.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.index', ['type' => $type])->withError($ex->getMessage());
        }
    }
}
