<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use App\Repositories\PageGroupRepository;
use App\Repositories\PageRepository;
use Exception;
use Illuminate\Http\Request;

class PageController extends Controller
{
    /**
     * @var PageRepository
     */
    protected $repository;

    /**
     * @var PageGroupRepository
     */
    protected $pageGroupRepository;

    public function __construct(PageRepository $repository, PageGroupRepository $pageGroupRepository)
    {
        $this->repository = $repository;
        $this->pageGroupRepository = $pageGroupRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Page::class);

        try {
            $pages = $this->repository->list();

            return view('backend.pages.index')
                ->with('pages', $pages);
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
        $this->authorize('manage', Page::class);

        try {
            $pageGroups = $this->pageGroupRepository->list();

            return view('backend.pages.create')
                ->with('pageGroups', $pageGroups);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.index')->withError($ex->getMessage());
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
        $this->authorize('manage', Page::class);

        try {
            $attributes = $request->all();

            $page = $this->repository->store($attributes);

            return redirect()->route('backend.pages.edit', $page)->withSuccess(__('resources.pages.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Page  $page
     * @return \Illuminate\Http\Response
     */
    public function edit(Page $page)
    {
        $this->authorize('manage', Page::class);

        try {
            $pageGroups = $this->pageGroupRepository->list();

            return view('backend.pages.edit')
                ->with('page', $page)
                ->with('pageGroups', $pageGroups);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Page  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Page $page)
    {
        $this->authorize('manage', Page::class);

        try {
            $attributes = $request->all();

            $page = $this->repository->update($page, $attributes);

            return redirect()->route('backend.pages.edit', $page)->withSuccess(__('resources.pages.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.edit', $page)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Page  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Page $page)
    {
        $this->authorize('manage', Page::class);

        try {
            $page = $this->repository->delete($page);

            return redirect()->route('backend.pages.index')->withSuccess(__('resources.pages.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.pages.index')->withError($ex->getMessage());
        }
    }
}
