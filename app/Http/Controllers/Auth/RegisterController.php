<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Mailers\AppMailer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;




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
    protected $redirectTo = '/home';

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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return User
    */
    protected function create(array $data)
    {

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => bcrypt($data['password']),
        ]);

        $mailer = new AppMailer();
        $mailer->sendEmailConfirmationTo($user);

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
        flash(trans('messages.you_have_verified_your_email'))->success();

        // add user to the group (s)he has been invited to before registering
        $invites = \App\Invite::where('email', '=', $user->email)->get();
        if ($invites) {
            foreach ($invites as $invite) {
                $membership = \App\Membership::firstOrNew(['user_id' => $user->id, 'group_id' => $invite->group_id]);
                $membership->membership = \App\Membership::MEMBER;
                $membership->save();
            }
        }

        return redirect('/');

    }
}
