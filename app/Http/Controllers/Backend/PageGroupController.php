<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\PageGroup;
use App\Repositories\PageGroupRepository;
use Exception;
use Illuminate\Http\Request;

class PageGroupController extends Controller
{
    /**
     * @var PageGroupRepository
     */
    protected $repository;

    public function __construct(PageGroupRepository $repository)
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
        $this->authorize('manage', PageGroup::class);

        try {
            $pageGroups = $this->repository->list();

            return view('backend.pages.groups.index')
                ->with('pageGroups', $pageGroups);
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
    public function create()
    {
        $this->authorize('manage', PageGroup::class);

        try {
            return view('backend.pages.groups.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.groups.index')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', PageGroup::class);

        try {
            $attributes = $request->all();

            $pageGroup = $this->repository->store($attributes);

            return redirect()->route('backend.pages.groups.edit', $pageGroup)->withSuccess(__('resources.page-groups.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.groups.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  PageGroup  $pageGroup
     * @return \Illuminate\Http\Response
     */
    public function edit(PageGroup $pageGroup)
    {
        $this->authorize('manage', PageGroup::class);

        try {
            return view('backend.pages.groups.edit')
                ->with('pageGroup', $pageGroup);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.groups.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  PageGroup  $pageGroup
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PageGroup $pageGroup)
    {
        $this->authorize('manage', PageGroup::class);

        try {
            $attributes = $request->all();

            $pageGroup = $this->repository->update($pageGroup, $attributes);

            return redirect()->route('backend.pages.groups.edit', $pageGroup)->withSuccess(__('resources.page-groups.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.groups.edit', $pageGroup)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  PageGroup  $pageGroup
     * @return \Illuminate\Http\Response
     */
    public function destroy(PageGroup $pageGroup)
    {
        $this->authorize('manage', PageGroup::class);

        try {
            $pageGroup = $this->repository->delete($pageGroup);

            return redirect()->route('backend.pages.groups.index')->withSuccess(__('resources.page-groups.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.groups.index')->withError($ex->getMessage());
        }
    }
}