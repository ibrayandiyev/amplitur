<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\AdditionalGroup;
use App\Repositories\AdditionalGroupRepository;
use Exception;
use Illuminate\Http\Request;

class AdditionalGroupController extends Controller
{
    /**
     * @var AdditionalGroupRepository
     */
    protected $repository;

    public function __construct(AdditionalGroupRepository $repository)
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
        try {
            $groups = $this->repository->list();

            return view('backend.additionals.groups.index')
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
    public function create(Request $request)
    {
        try {
            return view('backend.additionals.groups.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.groups.index')->withError($ex->getMessage());
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
            $group = $this->repository->setProvider(1)->store($attributes);

            return redirect()->route('backend.additionals.groups.edit', $group)->withSuccess(__('resources.additionals.groups.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.groups.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  AdditionalGroup  $additionalGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, AdditionalGroup $additionalGroup)
    {
        try {
            return view('backend.additionals.groups.edit')
                ->with('group', $additionalGroup);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.groups.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  AdditionalGroup  $additionalGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AdditionalGroup $additionalGroup)
    {
        try {
            $attributes = $request->toArray();
            $group = $this->repository->update($additionalGroup, $attributes);

            return redirect()->route('backend.additionals.groups.edit', $group)->withSuccess(__('resources.additionals.groups.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.groups.edit', $group)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  AdditionalGroup  $additionalGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(AdditionalGroup $additionalGroup)
    {
        try {
            $group = $this->repository->delete($additionalGroup);

            return redirect()->route('backend.additionals.groups.index')->withSuccess(__('resources.additionals.groups.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.additionals.groups.index')->withError($ex->getMessage());
        }
    }
}
