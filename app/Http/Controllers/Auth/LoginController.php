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
        sendLoginResponse as sendLoginResponseOriginal;
        sendFailedLoginResponse as sendFailedLoginResponseOriginal;
    }

    /**
     * @inheritDoc
     */
    protected function sendLoginResponse(Request $request)
    {
        if (request()->ajax()) {
            return [
                'success' => true,
            ];
        }

        $this->sendLoginResponseOriginal($request);

        return redirect('products');
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
     *dasd
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except(['logout', 'token']);
    }

    /**
     * The logout function.
     *
     * @return array
     */
    protected function loggedOut(Request $request)
    {
        session()->regenerate();

        if (request()->ajax()) {
            return [
                'success' => true,
            ];
        }

        return redirect('login');
    }

    public function token()
    {
        return [
            'success' => true,
            'token' => csrf_token(),
        ];
    }

}
