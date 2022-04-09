<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Inclusion;
use App\Repositories\CompanyRepository;
use App\Repositories\InclusionGroupRepository;
use App\Repositories\InclusionRepository;
use Exception;
use Illuminate\Http\Request;

class InclusionController extends Controller
{
    /**
     * @var InclusionRepository
     */
    protected $repository;

    /**
     * @var InclusionGroupRepository
     */
    protected $inclusionGroupRepository;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    public function __construct(InclusionRepository $repository, InclusionGroupRepository $inclusionGroupRepository, 
        CompanyRepository $companyRepository)
    {
        $this->repository = $repository;
        $this->inclusionGroupRepository = $inclusionGroupRepository;
        $this->companyRepository        = $companyRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(string $type)
    {
        $this->authorize('manage', Inclusion::class);

        try {
            $inclusions = $this->repository->setType($type)->list();

            return view('backend.inclusions.index')
                ->with('type', $type)
                ->with('inclusions', $inclusions);
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
        $this->authorize('manage', Inclusion::class);

        try {
            $groups     = $this->inclusionGroupRepository->setType($type)->list();
            $companies  = $this->companyRepository->list();

            return view('backend.inclusions.create')
                ->with('type', $type)
                ->with('companies', $companies)
                ->with('groups', $groups);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.index', ['type' => $type])->withError($ex->getMessage());
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
        $this->authorize('manage', Inclusion::class);

        try {
            $attributes = $request->toArray();
            $inclusion  = $this->repository->store($attributes);

            return redirect()->route('backend.inclusions.edit', ['type' => $type, $inclusion])->withSuccess(__('resources.inclusions.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.create', ['type' => $type])->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Inclusion  $inclusion
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type, Inclusion $inclusion)
    {
        $this->authorize('manage', Inclusion::class);

        try {
            $groups     = $this->inclusionGroupRepository->list();
            $companies  = $this->companyRepository->list();

            return view('backend.inclusions.edit')
                ->with('type', $type)
                ->with('companies', $companies)
                ->with('groups', $groups)
                ->with('inclusion', $inclusion);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  Inclusion  $inclusion
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, Inclusion $inclusion)
    {
        $this->authorize('manage', Inclusion::class);

        try {
            $attributes = $request->toArray();
            $inclusion = $this->repository->update($inclusion, $attributes);

            return redirect()->route('backend.inclusions.edit', ['type' => $type, $inclusion])->withSuccess(__('resources.inclusions.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.edit', ['type' => $type, $inclusion])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request    $request
     * @param  string     $type
     * @param  Inclusion  $inclusion
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $type, Inclusion $inclusion)
    {
        $this->authorize('manage', Inclusion::class);

        try {
            $inclusion = $this->repository->delete($inclusion);

            return redirect()->route('backend.inclusions.index', ['type' => $type])->withSuccess(__('resources.inclusions.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.inclusions.index', ['type' => $type])->withError($ex->getMessage());
        }
    }
}
