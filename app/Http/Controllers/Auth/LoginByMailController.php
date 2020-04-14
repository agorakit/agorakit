<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\User;
use Auth;
use URL;


class LoginByMailController extends Controller
{
    /**
    * Show a login by mail form
    */
    public function showLoginByMailForm()
    {
        return view('auth.loginbymail');
    }

    public function sendLoginByMail(Request $request)
    {

        $user = \App\User::where('email', $request->get('email'))->first();

        if ($user) {
            /* TODO
            return URL::temporarySignedRoute(
                'autologin', now()->addMinutes(30),
                ['username' => $user->username, 'redirect' => '/']
            );
            */
        }
        else {
            flash('No user found with this email, please create an account instead');
            return redirect()->back();
        }

    }


}
