<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    use AuthenticatesUsers {
        sendFailedLoginResponse as sendFailedLoginResponseOriginal;
    }

    /**
     * @inheritDoc
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        if (request()->ajax()) {
            return [
                'success' => false,
                'errors' => [
                    $this->username() => [trans('auth.failed')],
                ]
            ];
        }

        $this->sendFailedLoginResponseOriginal($request);
    }

    /**
     * Where to redirect users after login.
     * '/products'
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * The logout function.
     *
     * @return array
     */
    public function logout()
    {
        Auth::logout();

        return redirect()->route('login');
    }

}
