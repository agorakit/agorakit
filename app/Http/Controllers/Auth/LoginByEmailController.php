<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \App\User;
use Auth;
use URL;
use \App\Mail\LoginByEmail;
use Mail;


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
        $user = \App\User::where('email', $request->get('email'))->first();

        if ($user) {
            // send invitation email
            Mail::to($request->get('email'))->send(new LoginByEmail($user));
            flash('Check your mailbox, we sent you a login link. It will expire in 30 minutes');
            return redirect('/');
        }
        else {
            flash('No user found with this email, please create an account instead');
            return redirect()->back();
        }
    }
}
