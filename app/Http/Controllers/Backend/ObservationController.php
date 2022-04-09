<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Observation;
use App\Repositories\ObservationRepository;
use Exception;
use Illuminate\Http\Request;

class ObservationController extends Controller
{
    /**
     * @var ObservationRepository
     */
    protected $repository;

    public function __construct(ObservationRepository $repository)
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
        $this->authorize('manage', Exclusion::class);

        try {
            $observations = $this->repository->setType($type)->list();

            return view('backend.observations.index')
                ->with('type', $type)
                ->with('observations', $observations);
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
            return view('backend.observations.create')
                ->with('type', $type);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.observations.index', ['type' => $type])->withError($ex->getMessage());
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
            $observation = $this->repository->store($attributes);

            return redirect()->route('backend.observations.edit', ['type' => $type, $observation])->withSuccess(__('resources.observations.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.observations.create', ['type' => $type])->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Observation  $observation
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, string $type, Observation $observation)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            return view('backend.observations.edit')
                ->with('type', $type)
                ->with('observation', $observation);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.observations.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request      $request
     * @param  Observation  $observation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $type, Observation $observation)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $attributes = $request->toArray();
            $observation = $this->repository->update($observation, $attributes);

            return redirect()->route('backend.observations.edit', ['type' => $type, $observation])->withSuccess(__('resources.observations.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.observations.edit', ['type' => $type, $observation])->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request      $request
     * @param  string       $type
     * @param  Observation  $observation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, string $type, Observation $observation)
    {
        $this->authorize('manage', Exclusion::class);

        try {
            $observation = $this->repository->delete($observation);

            return redirect()->route('backend.observations.index', ['type' => $type])->withSuccess(__('resources.observations.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.observations.index', ['type' => $type])->withError($ex->getMessage());
        }
    }
}
