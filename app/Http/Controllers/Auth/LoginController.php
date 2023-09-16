<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use App\User;
use Mail;
use Auth;
use App\Mail\LoginByEmail;

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

    public function login(Request $request)
    {
        // decide if attempt is made with an email or a username
        $login = request()->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // find user
        $user = User::where($field, $login)->first();

        // check if password is not filled
        if (empty($request->input('password'))) {
            if ($user) {
                // send login link by email
                Mail::to($user->email)->send(new LoginByEmail($user));
                flash(__('Check your mailbox, we sent you a login link. It will expires in 30 minutes'));
                return redirect('/');
            } else {
                warning(__('No user found, please create an account instead'));
                return redirect()->back();
            }
        } else {
            if ($user) {
                // attempt to login
                if (Auth::attempt(['email' => $user->email, 'password' => $request->input('password')], request()->input('remember'))) {
                    return redirect()->intended('/');
                } else {
                    $this->incrementLoginAttempts($request);
                    error(__('Incorrect password and/or username'));
                    return redirect()->back();
                }
            } else {
                warning(__('No user found, please create an account instead'));
                return redirect()->back();
            }
        }
    }

    /**
     * Allows to log with either username or email
     */
    public function username()
    {
        $login = request()->input('login');
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$field => $login]);
        return $field;
    }
}
