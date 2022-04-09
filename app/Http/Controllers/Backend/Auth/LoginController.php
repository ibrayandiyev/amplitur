<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use App\Providers\RouteServiceProvider;
use Exception;
use \Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm()
    {
        return view('backend.auth.login');
    }

    /**
     * Attempt to log the user into the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        if (Auth::guard('users')->attempt(
            array_merge($this->credentials($request), ['status' => 'active' ]),
            $request->filled('remember')
        )) {
            return true;
        }

        
        if (Auth::guard('providers')->attempt(
            array_merge($this->credentials($request)),
            $request->filled('remember')
        )) {
            $_data = $this->credentials($request);
            $entity = app(Provider::class)->where("username", $_data['username'])->first();
            if(!$entity->isActive()){
                $this->guard()->logout();

                $request->session()->invalidate();

                $request->session()->regenerateToken();
                throw ValidationException::withMessages([
                    $this->username() => [trans('auth.not_active')],
                ]);
                return false;
            }
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
        if (Auth::guard('users')->user()) {
            return Auth::guard('users');
        }

        if (Auth::guard('providers')->user()) {
            return Auth::guard('providers');
        }
    }
}
