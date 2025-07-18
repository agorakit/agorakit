<?php
namespace Agorakit\Http\Controllers\Auth;

use Agorakit\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \Agorakit\User;
use Auth;
use URL;
use \Agorakit\Mail\LoginByEmail;
use Mail;


class LoginByEmailController extends Controller
{
    /**
    * Show a login by mail form, might be unnecessary now
    */
    public function showLoginByEmailForm(Request $request)
    {
        $email = $request->session()->get('email');
        return view('auth.loginbyemail')
        ->with('email', $email);
    }

    public function sendLoginByEmail(Request $request)
    {
        $user = User::where('email', trim($request->get('email')))->first();

        if ($user) {
            // send invitation email
            Mail::to($request->get('email'))->send(new LoginByEmail($user));
            flash('Check your mailbox, we sent you a login link. It will expires in 30 minutes');
            return redirect('/');
        }
        else {
            flash('No user found with this email, please create an account instead');
            return redirect()->back();
        }
    }
}
