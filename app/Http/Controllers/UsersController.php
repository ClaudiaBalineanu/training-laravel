<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;

class UsersController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('users', [
            'users' => User::all()->except(Auth::id())
        ]);
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function enableUser(User $user)
    {
        $user->enable();

        return redirect()->route('users');
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function disableUser(User $user)
    {
        $user->disable();

        return redirect()->route('users');
    }

    public function sendResetLinkEmail(Request $request, User $user)
    {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        $response = $this->broker()->sendResetLink(
            ['email' => $user->email]

        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);
    }

}
