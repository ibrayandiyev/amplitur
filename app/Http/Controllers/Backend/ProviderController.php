<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\Backend\ProviderRequest;
use App\Mail\Providers\ProviderValidMail;
use App\Models\Provider;
use App\Models\ProviderLog;
use App\Repositories\ProviderRepository;
use App\Repositories\CountryRepository;
use App\Repositories\ProviderLogRepository;
use App\Repositories\StateRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProviderController extends Controller
{
    /**
     * @var ProviderRepository
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

    public function __construct(
        ProviderRepository $repository, 
        CountryRepository $countryRepository,
        StateRepository $stateRepository,
        ProviderLogRepository $providerLogRepository
        )
    {
        $this->repository = $repository;
        $this->countryRepository = $countryRepository;
        $this->stateRepository = $stateRepository;
        $this->providerLogRepository = $providerLogRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if($this->checkAllow("onlyProvider", Provider::class)){
            $provider = auth()->user('providers');
            return redirect()->route('backend.providers.edit', $provider->id);
        }
        $this->authorize('manage', Provider::class);

        try {
            $providers = $this->repository->list();

            return view('backend.providers.index', compact('providers'));
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
        $this->authorize('manage', Provider::class);

        try {
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');

            return view('backend.providers.create', compact('countries', 'states'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.create')->withError($ex->getMessage());
        }
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  ProviderRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProviderRequest $request)
    {
        $this->authorize('manage', Provider::class);

        try {
            $attributes = $request->toArray();

            if (isset($attributes['birthdate'])) {
                $attributes['birthdate'] = convertDate($attributes['birthdate']);
            }

            $provider = $this->repository->store($attributes);

            return redirect()->route('backend.providers.edit', $provider)->withSuccess(__('resources.providers.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.create')->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function edit(Provider $provider)
    {
        try {
            if(!$this->checkAllow('view', [Provider::class, $provider])){
                $this->authorize('manage', Provider::class);
            }
            $address = $provider->address;
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');

            $providerLogs = $this->getLogs($provider);

            return view('backend.providers.edit')
                ->with('provider', $provider)
                ->with('countries', $countries)
                ->with('states', $states)
                ->with('address', $address)
                ->with('providerLogs', $providerLogs);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.index')->withError($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  ProviderRequest $request
     * @param  Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function update(ProviderRequest $request, Provider $provider)
    {
        if(!$this->checkAllow('view', [Provider::class, $provider])){
            $this->authorize('manage', Provider::class);
        }

        try {
            $attributes = $request->toArray();

            $provider = $this->repository->update($provider, $attributes);

            return redirect()->route('backend.providers.edit', $provider->id)->withSuccess(__('resources.providers.updated'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.edit', $provider->id)->withError($ex->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Provider  $provider
     * @return \Illuminate\Http\Response
     */
    public function destroy(Provider $provider)
    {
        $this->authorize('manage', Provider::class);

        try {
            $provider = $this->repository->delete($provider);

            return redirect()->route('backend.providers.index')->withSuccess(__('resources.providers.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.index')->withError($ex->getMessage());
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
        $this->authorize('manage', Provider::class);

        try {
            $params = $request->all();

            if (isset($params['created_at'][0])) {
                $params['created_at'][0] = convertDate($params['created_at'][0]);
            }

            if (isset($params['created_at'][1])) {
                $params['created_at'][1] = convertDate($params['created_at'][1]);
            }

            $providers = $this->repository->filter($params);

            if (isset($params['created_at'][0])) {
                $params['created_at'][0] = convertDate($params['created_at'][0], true);
            }

            if (isset($params['created_at'][1])) {
                $params['created_at'][1] = convertDate($params['created_at'][1], true);
            }


            return view('backend.providers.index', compact('providers', 'params'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.index')->withError($ex->getMessage());
        }
    }

    /**
     * [register description]
     *
     * @return  [type]  [return description]
     */
    public function register()
    {
        if (auth('users')->check() || auth('providers')->check()) {
            return redirect()->route('backend.index');
        }

        try {
            $countries = $this->countryRepository->list();
            $states = $this->stateRepository->getByCountryCode('BR');
        
            return view('backend.providers.register')
                ->with('countries', $countries)
                ->with('states', $states);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.index')->withError($ex->getMessage());
        }
    }

    /**
     * [doRegister description]
     *
     * @param   ProviderRequest  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function doRegister(ProviderRequest $request)
    {
        if (auth('users')->check() || auth('providers')->check()) {
            return redirect()->route('backend.index');
        }

        try {
            $attributes = $request->all();

            if (isset($attributes['birthdate'])) {
                $attributes['birthdate'] = convertDate($attributes['birthdate']);
            }

            $provider = $this->repository->register($attributes);

            return view('backend.providers.welcome')
                ->with('provider', $provider);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.register')->withError($ex->getMessage());
        }
    }

    /**
     * [getLogs description]
     *
     * @param   Client  $client  [$client description]
     *
     * @return  [type]           [return description]
     */
    public function getLogs(Provider $provider)
    {
        if(!$this->checkAllow('view', [Provider::class, $provider])){
            $this->authorize('manage', Provider::class);
        }

        return $this->providerLogRepository->getByTargetPRovider(
            $provider,
            auth('users')->user() ?? auth('providers')->user()
        );
    }

    /**
     * [storeLog description]
     *
     * @param   Request   $request  [$request description]
     * @param   Provider  $provider [$provider description]
     *
     * @return  [type]             [return description]
     */
    public function storeLog(Request $request, Provider $provider)
    {
        $this->authorize('manage', Provider::class);

        try {
            $attributes = $request->all();

            $targetProvider = $provider;
            $provider = auth('providers')->user();
            $user = auth('users')->user();

            $this->providerLogRepository
                ->setTargetProvider($targetProvider)
                ->setProvider($provider)
                ->setUser($user)
                ->store([
                    'message' => $attributes['log']['message'],
                    'level' => $attributes['log']['level'],
                    'ip' => ip(),
                    'type' => 'manual',
                ]);

            return redirect()->route('backend.providers.edit', $targetProvider)->withSuccess(__('resources.logs.created'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.edit', $targetProvider)->withError($ex->getMessage());
        }
    }

    /**
     * [destroyLog description]
     *
     * @param   Request  $request  [$request description]
     * @param   Provider   $client   [$client description]
     *
     * @return  [type]             [return description]
     */
    public function destroyLog(Request $request, Provider $provider)
    {
        $this->authorize('delete', ProviderLog::class);

        try {
            $attributes = $request->all();

            $this->providerLogRepository->deleteMany($attributes['deleteLogs']);

            return redirect()->route('backend.providers.edit', $provider)->withSuccess(__('resources.logs.deleted'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('backend.providers.edit', $provider)->withError($ex->getMessage());
        }
    }

    /**
     * [validateToken description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function validateToken(Request $request, $validationToken)
    {
        
            $attributes = $request->all();

            $provider   = $this->repository->validateToken(['validation_token' => $validationToken]);
            if($provider != null){
                Mail::to($provider->email)->send(new ProviderValidMail($provider));
            }
            return view('backend.providers.validate_token', ['provider' => $provider])
            ;
    }

    
}
