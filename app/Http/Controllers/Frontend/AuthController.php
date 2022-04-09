<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\DocumentType;
use App\Enums\PersonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Clients\PasswordChangeRequest;
use App\Http\Requests\Frontend\RegisterRequest;
use App\Mail\ClientValidMail;
use App\Repositories\ClientRepository;
use App\Repositories\CountryRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * @var CountryRepository
     */
    protected $countryRepository;

    /**
     * @var ClientRepository
     */
    protected $clientRepository;

    public function __construct(CountryRepository $countryRepository, ClientRepository $clientRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->clientRepository = $clientRepository;
    }

    /**
     * [login description]
     *
     * @return  [type]  [return description]
     */
    public function login()
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        return view('frontend.auth.login');
    }

    /**
     * [recovery description]
     *
     * @return  [type]  [return description]
     */
    public function recovery()
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        return view('frontend.auth.recovery');
    }

    /**
     * [recovery description]
     *
     * @return  [type]  [return description]
     */
    public function register()
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        $countries = $this->countryRepository->list();

        return view('frontend.auth.register')
            ->with('countries', $countries);
    }

    /**
     * [doRegister description]
     *
     * @param   RegisterRequest  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function doRegister(RegisterRequest $request)
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        try {
            $attributes = $request->all();

            $attributes = $this->parseRegisterRequest($attributes);

            $client = $this->clientRepository->store($attributes);

            return view('frontend.auth.welcome')
                ->with('email', $client->email);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withInput()->withError($ex->getMessage());
        }
    }

    /**
     * [doRecoveryPassword description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function doRecoveryPassword(Request $request)
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        try {
            $attributes = $request->all();
            $email      = $attributes['email'];

            $return = $this->clientRepository->recoveryPassword($email);

            if(!$return){
                return redirect()->route(getRouteByLanguage('frontend.auth.recovery'))->withError(__('frontend.misc.email_not_exists'));
            }
            return redirect()->route(getRouteByLanguage('frontend.auth.recovery'))->withSuccess(__('frontend.misc.verifique_email_senha'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [doNewPassword description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function viewNewPassword(Request $request)
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        try {
            $attributes = $request->all();
            $token      = $attributes['token'];

            $client     = $this->clientRepository->findBy(["remember_token" => $token]);

            if(!$client){
                throw new Exception(__('frontend.misc.erro_solicitacao_senha'));
            }

            return view('frontend.auth.change_password')
                ->with('client', $client)
                ->with('token', $token);
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route(getRouteByLanguage('frontend.auth.recovery'))->withError($ex->getMessage());
        }
    }

    /**
     * [doNewPassword description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function doNewPassword(PasswordChangeRequest $request)
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        try {
            $_data = $request->all();

            if(!$this->clientRepository->doChangePassword($_data)){
                throw new Exception(__('frontend.auth.failed_change_password'));
            };

            return redirect()->route(getRouteByLanguage('frontend.auth.recovery'))->withSuccess(__('frontend.misc.senha_alterada_sucesso'));
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    /**
     * [doRecoveryUsername description]
     *
     * @param   Request  $request  [$request description]
     *
     * @return  [type]             [return description]
     */
    public function doRecoveryUsername(Request $request)
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        try {
            $attributes = $request->all();
            $email = $attributes['email'];

            $return = $this->clientRepository->recoveryUsername($email);
            if(!$return){
                return redirect()->route(getRouteByLanguage('frontend.auth.recovery'))->withError(__('frontend.misc.email_not_exists'));
            }
            return redirect()->route(getRouteByLanguage('frontend.auth.recovery'))->withSuccess(__('frontend.misc.msg_recover_login', ['email' => $email]) );
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return back()->withError($ex->getMessage());
        }
    }

    public function doVerifyAccount(Request $request)
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        try {
            $token = $request->get('token');

            $client = $this->clientRepository->verifyAccount($token);

            if ($client) {
                auth('clients')->login($client);
            }

            return redirect()->route('frontend.index');
        } catch (Exception $ex) {
            bugtracker()->notifyException($ex);
            return redirect()->route('frontend.index')->withError($ex->getMessage());
        }
    }


    /**
     * [parseRegisterRequest description]
     *
     * @param   array  $attributes  [$attributes description]
     *
     * @return  array               [return description]
     */
    private function parseRegisterRequest(array $attributes): array
    {
        $parsed['name'] = $attributes['type'] == PersonType::FISICAL ? $attributes['name'] : null;
        $parsed['company_name'] = $attributes['type'] == PersonType::LEGAL ? $attributes['name'] : null;

        unset($attributes['est_amissor'], $attributes['submit']);

        $attributes = array_merge($attributes, $parsed);

        return $attributes;
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

        $client   = $this->clientRepository->validateToken(['validation_token' => $validationToken]);
        if($client != null){
            Mail::to($client->email)->send(new ClientValidMail($client));
        }
        return view('frontend.auth.validate_token', ['client' => $client])
        ;
    }
}
