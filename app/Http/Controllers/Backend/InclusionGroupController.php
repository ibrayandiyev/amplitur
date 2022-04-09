<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Inclusion;
use App\Models\InclusionGroup;
use App\Repositories\InclusionGroupRepository;
use Exception;
use Illuminate\Http\Request;

class InclusionGroupController extends Controller
{
    /**
     * @var InclusionGroupRepository
     */
    protected $repository;

    public function __construct(InclusionGroupRepository $repository)
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
            $groups = $this->repository->setType($type)->list();

            return view('backend.inclusions.groups.index')
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
            return view('backend.inclusions.groups.create')
                ->with('type', $type);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.groups.index', ['type' => $type])->withError($ex->getMessage());
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

            return redirect()->route('backend.inclusions.groups.edit', ['type' => $type, $group])->withSuccess(__('resources.inclusions.groups.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.groups.create', ['type' => $type])->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  InclusionGroup  $inclusionGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type, InclusionGroup $inclusionGroup)
    {
        try {
            return view('backend.inclusions.groups.edit')
                ->with('type', $type)
                ->with('group', $inclusionGroup);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.groups.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  InclusionGroup  $inclusionGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, InclusionGroup $inclusionGroup)
    {
        try {
            $attributes = $request->toArray();
            $group = $this->repository->update($inclusionGroup, $attributes);

            return redirect()->route('backend.inclusions.groups.edit', ['type' => $type, $group])->withSuccess(__('resources.inclusions.groups.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.groups.edit', ['type' => $type, $group])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  InclusionGroup  $inclusionGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $type, InclusionGroup $inclusionGroup)
    {
        try {
            $group = $this->repository->delete($inclusionGroup);

            return redirect()->route('backend.inclusions.groups.index', ['type' => $type])->withSuccess(__('resources.inclusions.groups.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.groups.index', ['type' => $type])->withError($ex->getMessage());
        }
    }
}
