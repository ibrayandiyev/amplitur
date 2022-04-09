<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Enums\Country;
use App\Enums\Currency;
use App\Enums\Language;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\CurrencyRepository;
use \Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LoginController extends Controller
{
    private $currencyRepository;
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Request $request,
        CurrencyRepository $currencyRepository)
    {
        $this->currencyRepository = $currencyRepository;
        parent::__construct($request);
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    public function showLoginForm()
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        return view('frontend.auth.login');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if (auth('clients')->check()) {
            return redirect()->route(getRouteByLanguage('frontend.my-account.index'));
        }

        if (Auth::guard('clients')->attempt(
            $this->credentials($request),
            $request->filled('remember')
        )) {
            // Set the default language based on rules
            $user       = auth('clients')->user();
            $language   = Language::PORTUGUESE;
            $currency   = Currency::REAL;
            switch($user->country){
                case Country::BRAZIL:
                    $currency = $this->currencyRepository->findByCode(Currency::REAL);
                    $language = Language::PORTUGUESE;
                    break;
                default:
                    $currency = $this->currencyRepository->findByCode(Currency::EURO);
                    switch($user->language){
                        case Language::ENGLISH:
                            $language = Language::ENGLISH;
                            break;
                        case Language::SPANISH:
                            $language = Language::SPANISH;
                            break;
                    }
                    break;                
                
            }
            currency($currency);
            language($language);
            return true;
        }

        return false;
    }

    /**
     * @inherited
     */
    public function username()
    {
        return 'username';
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('clients');
    }

    /**
     * Get the csrf token 
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function refreshCsrf()
    {
        $cookie_sent    = Cookie::get('csrfToken');
        if($cookie_sent == null ){
            $csrfToken =  csrf_token();
            Cookie::queue(Cookie::make('csrfToken', $csrfToken, 4));    // 4 minutes
            return $csrfToken;
        }else{
            return $cookie_sent;
        }
        
    }
}
