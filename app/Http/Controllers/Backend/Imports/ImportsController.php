<?php

namespace App\Http\Controllers\Backend\Imports;

use App\Http\Controllers\Controller;
use App\Services\Imports\ImportClientsService;
use App\Services\Imports\ImportReservationsService;
use Illuminate\Http\Request;

class ImportsController extends Controller
{

    public function __construct(
        
        )
    {
       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return response('Not found', 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return response('Not found', 404);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return response('Not found', 404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        return response('Not found', 404);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request      $request
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        return response('Not found', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request      $request
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        return response('Not found', 404);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request      $request
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function import_clients(Request $request)
    {
        $service = app(ImportClientsService::class);
        $service->run();
    }

        /**
     * Remove the specified resource from storage.
     *
     * @param  Request      $request
     * @param  Additional  $additional
     * @return \Illuminate\Http\Response
     */
    public function import_reservation(Request $request)
    {
        $service = app(ImportReservationsService::class);
        $service->run();
    }
}
