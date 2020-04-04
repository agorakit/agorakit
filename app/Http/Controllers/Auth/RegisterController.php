<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Request;
use Auth;
use Mail;
use App\Mail\UserConfirmation;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
    * Where to redirect users after registration.
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
          $this->middleware('guest', ['except' => ['confirmEmail']]);
    }

    /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return \App\User
    */
    protected function create(array $data)
    {
        $user =  User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        Mail::to($user)->send(new UserConfirmation($user));

        return $user;
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

        // add user to the group (s)he has been invited to before registering
        $invites = \App\Invite::where('email', '=', $user->email)->get();
        if ($invites) {
            foreach ($invites as $invite) {
                $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $invite->group_id]);
                $membership->membership = \App\Membership::MEMBER;
                $membership->save();

                // mark the invite as claimed now
                $invite->claimed_at = Carbon::now();
                $invite->save();
            }
        }

        return redirect('/');
    }

}
