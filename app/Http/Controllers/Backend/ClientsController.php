<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ClientRequest;
use App\Models\Client;
use App\Models\ClientLog;
use App\Repositories\ClientLogRepository;
use App\Repositories\ClientRepository;
use App\Repositories\CountryRepository;
use App\Repositories\StateRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientsController extends Controller
{
    /**
     * @var ClientRepository
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

    /**
     * @var ClientLogRepository
     */
    protected $clientLogRepository;

    public function __construct(
        ClientRepository $repository,
        CountryRepository $countryRepository,
        StateRepository $stateRepository,
        ClientLogRepository $clientLogRepository
        )
    {
        $this->repository = $repository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->clientLogRepository = $clientLogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('manage', Client::class);

        try {
            $clients = $this->repository->list(200);

            return view('backend.clients.index', compact('clients'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('manage', Client::class);

        try {
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');

            return view('backend.clients.create', compact('countries', 'states'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.create')->withError($ex->getMessage());
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ClientRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ClientRequest $request)
    {
        $this->authorize('manage', Client::class);

        try {
            $attributes = $request->toArray();

            if (isset($attributes['birthdate'])) {
                $attributes['birthdate'] = convertDate($attributes['birthdate']);
            }

            $client = $this->repository->store($attributes);

            return redirect()->route('backend.clients.edit', $client)->withSuccess(__('resources.clients.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.create')->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Client  $client
     * 
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $this->authorize('manage', Client::class);

        try {
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');
            $address = $client->address;

            $clientLogs = $this->getLogs($client);

            return view('backend.clients.edit')
                ->with('client', $client)
                ->with('countries', $countries)
                ->with('states', $states)
                ->with('address', $address)
                ->with('clientLogs', $clientLogs);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ClientRequest $request
     * @param  Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(ClientRequest $request, Client $client)
    {
        $this->authorize('manage', Client::class);

        try {
            $attributes = $request->toArray();

            if (isset($attributes['birthdate'])) {
                $attributes['birthdate'] = convertDate($attributes['birthdate']);
            }

            $client = $this->repository->update($client, $attributes);

            return redirect()->route('backend.clients.edit', $client->id)->withSuccess(__('resources.clients.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.edit', $client->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $this->authorize('manage', Client::class);

        try {
            $client = $this->repository->delete($client);

            return redirect()->route('backend.clients.index')->withSuccess(__('resources.clients.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.index')->withError($ex->getMessage());
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
        $this->authorize('manage', Client::class);

        try {
            $params = $request->except(['_token']);

            $clients = $this->repository->filter($params);

            if(isset($params["email_list"])){
                return view('backend.clients.exports.email_list', compact('clients', 'params'));
            }
            if(isset($params["exportar"])){
                return $this->export($request);
            }
            return view('backend.clients.index', compact('clients', 'params'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.index')->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * Export the specified resource collection from storage
     *
     * @param   Request  $request
     *
     * @return  \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $this->authorize('manage', Client::class);

        try {
            $params = $request->except(['_token']);

            return $this->repository->download($params);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.index')->withError($ex->getMessage());
        }
    }

    /**
     * [getLogs description]
     *
     * @param   Client  $client  [$client description]
     *
     * @return  [type]           [return description]
     */
    public function getLogs(Client $client)
    {
        $this->authorize('manage', ClientLog::class);

        return $this->clientLogRepository->getByTargetClient(
            $client,
            auth('users')->user() ?? auth('providers')->user()
        );
    }

    /**
     * [storeLog description]
     *
     * @param   Request  $request  [$request description]
     * @param   Client   $client   [$client description]
     *
     * @return  [type]             [return description]
     */
    public function storeLog(Request $request, Client $client)
    {
        $this->authorize('manage', ClientLog::class);

        try {   
            $attributes = $request->all();

            $provider = auth('providers')->user();
            $user = auth('users')->user();

            $this->clientLogRepository
                ->setTargetClient($client)
                ->setProvider($provider)
                ->setUser($user)
                ->store([
                    'message' => $attributes['log']['message'],
                    'level' => $attributes['log']['level'],
                    'ip' => ip(),
                    'type' => 'manual',
                ]);

            return redirect()->route('backend.clients.edit', $client)->withSuccess(__('resources.logs.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.edit', $client)->withError($ex->getMessage());
        }
    }

    /**
     * [destroyLog description]
     *
     * @param   Request  $request  [$request description]
     * @param   Client   $client   [$client description]
     *
     * @return  [type]             [return description]
     */
    public function destroyLog(Request $request, Client $client)
    {
        $this->authorize('delete', ClientLog::class);

        try {
            $attributes = $request->all();

            $this->clientLogRepository->deleteMany($attributes['deleteLogs']);
            
            return redirect()->route('backend.clients.edit', $client)->withSuccess(__('resources.logs.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.edit', $client)->withError($ex->getMessage());
        }
    }

    /**
     * [loginAsCustomer description]
     *
     * @param   Request  $request  [$request description]
     * @param   Client   $client   [$client description]
     *
     * @return  [type]             [return description]
     */
    public function loginAsCustomer(Request $request, Client $client)
    {
        $this->authorize('manage', Client::class);

        try {
            if (Auth::guard('clients')->loginUsingId(
                $client->id,
                $request->filled('remember')
            )) {
                return redirect()->route(getRouteByLanguage('frontend.my-account.index'))->withSuccess(__('resources.clients.loggedin'));
            }else{
                throw new Exception(__('resources.clients.failed_to_login'));
            }
            
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.clients.index', $client)->withError($ex->getMessage());
        }
    }
}
