<?php

namespace App\Http\Controllers\Backend\Auth;

use App\Enums\Guards;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Repositories\Traits\Password\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    private $guard = Guards::USERS;


    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::DASHBOARD;

    /**
     * @inherited
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('backend.auth.reset')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }
}
