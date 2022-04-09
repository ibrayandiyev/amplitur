<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\ExclusionGroup;
use App\Repositories\ExclusionGroupRepository;
use Exception;
use Illuminate\Http\Request;

class ExclusionGroupController extends Controller
{
    /**
     * @var ExclusionGroupRepository
     */
    protected $repository;

    public function __construct(ExclusionGroupRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $type)
    {
        try {
            $groups = $this->repository->list();

            return view('backend.exclusions.groups.index')
                ->with('type', $type)
                ->with('groups', $groups);
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
        try {
            return view('backend.exclusions.groups.create')
                ->with('type', $type);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.groups.index', ['type' => $type])->withError($ex->getMessage());
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
        try {
            $attributes = $request->toArray();
            $group = $this->repository->store($attributes);

            return redirect()->route('backend.exclusions.groups.edit', ['type' => $type, $group])->withSuccess(__('resources.exclusions.groups.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.groups.create', ['type' => $type])->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  ExclusionGroup  $exclusionGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type, ExclusionGroup $exclusionGroup)
    {
        try {
            return view('backend.exclusions.groups.edit')
                ->with('type', $type)
                ->with('group', $exclusionGroup);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.groups.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  ExclusionGroup  $exclusionGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, ExclusionGroup $exclusionGroup)
    {
        try {
            $attributes = $request->toArray();
            $group = $this->repository->update($exclusionGroup, $attributes);

            return redirect()->route('backend.exclusions.groups.edit', ['type' => $type, $group])->withSuccess(__('resources.exclusions.groups.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.groups.edit', ['type' => $type, $group])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  ExclusionGroup  $exclusionGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $type, ExclusionGroup $exclusionGroup)
    {
        try {
            $group = $this->repository->delete($exclusionGroup);

            return redirect()->route('backend.exclusions.groups.index', ['type' => $type])->withSuccess(__('resources.exclusions.groups.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.exclusions.groups.index', ['type' => $type])->withError($ex->getMessage());
        }
    }
}
