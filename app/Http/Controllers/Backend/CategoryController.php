<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\CategoryRequest;
use App\Http\Requests\Backend\ImportCategorysRequest;
use App\Models\Category;
use App\Repositories\CategoryRepository;
use App\Repositories\CountryRepository;
use App\Repositories\StateRepository;
use Exception;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * @var CategoryRepository
     */
    protected $repository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var StateRepository
     */
    protected $stateRepository;

    public function __construct(CategoryRepository $repository, CountryRepository $countryRepository, StateRepository $stateRepository)
    {
        $this->repository = $repository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Category::class);

        $categories = $this->repository->list();

        return view('backend.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', Category::class);

        try {
            $categories = $this->repository->list();

            return view('backend.categories.create', compact('categories'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.categories.create')->withError($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  CategoryRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', Category::class);

        try {
            $attributes = $request->toArray();

            $category = $this->repository->store($attributes);

            return redirect()->route('backend.categories.edit', $category)->withSuccess(__('resources.categories.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.categories.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $this->authorize('manage', Category::class);

        try {
            $categories = $this->repository->list();

            return view('backend.categories.edit', compact('category', 'categories'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.categories.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  CategoryRequest $request
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(CategoryRequest $request, Category $category)
    {
        $this->authorize('manage', Category::class);

        try {
            $attributes = $request->toArray();

            $category = $this->repository->update($category, $attributes);

            return redirect()->route('backend.categories.edit', $category->id)->withSuccess(__('resources.categories.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.categories.edit', $category->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $this->authorize('manage', Category::class);

        try {
            $category = $this->repository->delete($category);

            return redirect()->route('backend.categories.index')->withSuccess(__('resources.categories.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.categories.index')->withError($ex->getMessage());
        }
    }
}
