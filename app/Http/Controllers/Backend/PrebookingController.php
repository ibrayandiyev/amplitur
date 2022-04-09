<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Models\Prebooking;
use App\Repositories\ClientRepository;
use App\Repositories\CountryRepository;
use App\Repositories\PrebookingRepository;
use Exception;
use Illuminate\Http\Request;

class PrebookingController extends Controller
{
    /**
     * @var PrebookingRepository
     */
    protected $prebookingRepository;

    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    public function __construct(
        PrebookingRepository $prebookingRepository,
        CountryRepository $countryRepository,
        ClientRepository $clientRepository)
    {
        $this->prebookingRepository = $prebookingRepository;    
        $this->countryRepository = $countryRepository; 
        $this->clientRepository = $clientRepository;   
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Prebooking::class);

        try {
            $prebookings = $this->prebookingRepository->list();
            $countries = $this->countryRepository->list();

            return view('backend.prebookings.index')
                ->with('countries', $countries)
                ->with('prebookings', $prebookings);

        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);

            return redirect()->route('backend.index')->withErrors($ex->getMessage());
        }
    }

    /**
     * Filter the specified resource from storage
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function filter(Request $request)
    {
        $this->authorize('manage', Prebooking::class);

        try {
            $params = $request->all();

            if ($params['created_at'][0]) {
                $params['created_at'][0] = convertDate($params['created_at'][0]);
            }

            if ($params['created_at'][1]) {
                $params['created_at'][1] = convertDate($params['created_at'][1]);
            }

            $prebookings = $this->prebookingRepository->filter($params);
            $countries = $this->countryRepository->list();

            if ($params['created_at'][0]) {
                $params['created_at'][0] = convertDate($params['created_at'][0], true);
            }

            if ($params['created_at'][1]) {
                $params['created_at'][1] = convertDate($params['created_at'][1], true);
            }

            return view('backend.prebookings.index')
                ->with('prebookings', $prebookings)
                ->with('countries', $countries)
                ->with('params', $params);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.prebookings.index')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', Prebooking::class);

        try {
            $countries = $this->countryRepository->list();
            $clients = $this->clientRepository->list();

            return view('backend.prebookings.create')
                ->with('clients', $clients)
                ->with('countries', $countries);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.prebookings.index')->withErrors($ex->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Prebooking $prebooking
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('manage', Prebooking::class);

        try {
            $attributes = $request->all();
            $prebooking = $this->prebookingRepository->store($attributes);

            return redirect()->route('backend.prebookings.edit', $prebooking)->withSuccess(__('resources.prebookings.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.prebookings.create')->withErrors($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Prebooking $prebooking)
    {
        $this->authorize('manage', Prebooking::class);

        try {
            $countries = $this->countryRepository->list();
            $clients = $this->clientRepository->list();

            return view('backend.prebookings.edit')
                ->with('countries', $countries)
                ->with('clients', $clients)
                ->with('prebooking', $prebooking);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.prebookings.index')->withErrors($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Prebooking $prebooking)
    {
        $this->authorize('manage', Prebooking::class);

        try {
            $attributes = $request->all();
            $prebooking = $this->prebookingRepository->update($prebooking, $attributes);

            return redirect()->route('backend.prebookings.edit', $prebooking)->withSuccess(__('resources.prebookings.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.prebookings.create')->withErrors($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Prebooking  $prebooking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prebooking $prebooking)
    {
        $this->authorize('manage', Prebooking::class);

        try {
            $prebooking = $this->prebookingRepository->delete($prebooking);

            return redirect()->route('backend.prebookings.index')->withSuccess(__('resources.prebookings.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.prebookings.index')->withErrors($ex->getMessage());
        }
    }
}
