<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\LoginByEmail;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Mail;
use URL;

class LoginByEmailController extends Controller
{
    /**
     * Show a login by mail form
     */
    public function showLoginByEmailForm(Request $request)
    {
        $email = $request->session()->get('email');

        return view('auth.loginbyemail')
        ->with('email', $email);
    }

    public function sendLoginByEmail(Request $request)
    {
        $user = \App\Models\User::where('email', $request->get('email'))->first();

        if ($user) {
            // send invitation email
            Mail::to($request->get('email'))->send(new LoginByEmail($user));
            flash('Check your mailbox, we sent you a login link. It will expires in 30 minutes');

            return redirect('/');
        } else {
            flash('No user found with this email, please create an account instead');

            return redirect()->back();
        }
    }
}
