<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;



use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

use App\User;
use Auth;
use Mail;
use Gate;
use App\Mail\UserConfirmation;

class RegisterController extends Controller
{

    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['confirmEmail']]);
    }

    /**
    * Step 1 : Show registration form
    */
    public function showRegistrationForm()
    {
        if (Gate::allows('create', User::class)) {
            return view('auth.register');
        }
        else {
            abort(500, 'You cannot create an account on this server');
        }
    }

    /**
    * Step 2 : store email and name from the previous form
    * Redirect user to email login if account found, else redirecto to Step 3
    */
    public function handleRegistrationForm(Request $request)
    {
        Gate::authorize('create', User::class);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
        ]);



        // store name and email in the session
        $request->session()->put('name', $request->input('name'));
        $request->session()->put('email', $request->input('email'));


        // check if mail is taken, if taken, propose a login link instead
        $user = User::where('email', $request->input('email'))->first();
        if($user) {
            flash(trans('You already have an account, use this form to receive a login link by email'));
            return redirect()->route('loginbyemail');
        }

        // check if mail is taken and user account deleted
        
         $user = User::withTrashed()->where('email', $request->input('email'))->first();
         if($user) {
             flash(trans('Your user account is marked for deletion, contact an admin if you want to re-activate your account'));
             return redirect('/');
            }

        // else go to step 3 : we ask for the passwords
        return redirect('/register/password');
    }

    /**
    * Step 3 : Show passwords form
    */
    public function showPasswordForm(Request $request)
    {
        Gate::authorize('create', User::class);

        return view('auth.register_password');
    }


    /**
    * Step 4 : Handle everything and hopefuly create an account
    */
    public function handlePasswordForm(Request $request)
    {
        Gate::authorize('create', User::class);

        $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user =  User::create([
            'name' => $request->session()->get('name'),
            'email' => $request->session()->get('email'),
            'password' => Hash::make($request->input('password')),
        ]);

        Mail::to($user)->send(new UserConfirmation($user));
        Auth::login($user);

        return redirect('/');
    }




    /**
    * Confirm a user's email address.
    *
    * @param string $token
    *
    * @return mixed
    */
    public function confirmEmail(Request $request, $token)
    {
        // find user based on the verif token
        $user = User::whereToken($token)->firstOrFail();

        // confirm and login if not logged already
        $user->confirmEmail();
        if (Auth::guest()) {
            Auth::login($user); //TODO security implication of this
        }
        flash(trans('messages.you_have_verified_your_email'));


        return redirect('/');
    }

}
