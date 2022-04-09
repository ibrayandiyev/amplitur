<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\SaleCoefficient;
use App\Repositories\SaleCoefficientRepository;
use Exception;
use Illuminate\Http\Request;

class SaleCoefficientController extends Controller
{
    /**
     * @var SaleCoefficientRepository
     */
    protected $repository;

    public function __construct(SaleCoefficientRepository $repository)
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
        $this->authorize('manage', SaleCoefficient::class);

        try {
            $saleCoefficients = $this->repository->listOrdered();

            return view('backend.saleCoefficients.index')->with('saleCoefficients', $saleCoefficients);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withErrors($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', SaleCoefficient::class);

        try {
            return view('backend.saleCoefficients.create');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.saleCoefficients.create')->withError($ex->getMessage());
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
        $this->authorize('manage', SaleCoefficient::class);

        try {
            $attributes = $request->toArray();

            $saleCoefficient = $this->repository->store($attributes);

            return redirect()->route('backend.saleCoefficients.edit', $saleCoefficient)->withSuccess(__('resources.saleCoefficients.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.saleCoefficients.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  SaleCoefficient  $saleCoefficient
     * @return \Illuminate\Http\Response
     */
    public function edit(SaleCoefficient $saleCoefficient)
    {
        $this->authorize('manage', SaleCoefficient::class);

        try {
            return view('backend.saleCoefficients.edit')->with('saleCoefficient', $saleCoefficient);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.saleCoefficients.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request $request
     * @param  SaleCoefficient  $saleCoefficient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SaleCoefficient $saleCoefficient)
    {
        $this->authorize('manage', SaleCoefficient::class);

        try {
            $attributes = $request->toArray();

            $saleCoefficient = $this->repository->update($saleCoefficient, $attributes);

            return redirect()->route('backend.saleCoefficients.edit', $saleCoefficient)->withSuccess(__('resources.saleCoefficients.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.saleCoefficients.edit', $saleCoefficient)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  SaleCoefficient  $saleCoefficient
     * @return \Illuminate\Http\Response
     */
    public function destroy(SaleCoefficient $saleCoefficient)
    {
        $this->authorize('manage', SaleCoefficient::class);

        try {
            $saleCoefficient = $this->repository->delete($saleCoefficient);

            return redirect()->route('backend.saleCoefficients.index')->withSuccess(__('resources.saleCoefficients.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.saleCoefficients.index')->withError($ex->getMessage());
        }
    }
}
