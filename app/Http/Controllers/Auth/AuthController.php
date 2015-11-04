<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use App\Mailers\AppMailer;
use Illuminate\Http\Request;

class AuthController extends Controller
{
  /*
  |--------------------------------------------------------------------------
  | Registration & Login Controller
  |--------------------------------------------------------------------------
  |
  | This controller handles the registration of new users, as well as the
  | authentication of existing users. By default, this controller uses
  | a simple trait to add these behaviors. Why don't you explore it?
  |
  */

  use AuthenticatesAndRegistersUsers, ThrottlesLogins;

  /**
  * Create a new authentication controller instance.
  *
  * @return void
  */
  public function __construct()
  {
    $this->middleware('guest', ['except' => ['getLogout', 'confirmEmail']]);
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
      'password' => 'required|confirmed|min:6',
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

      //dd($data);
      $user = User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => bcrypt($data['password']),
        ]);

        $mailer = new AppMailer;
        $mailer->sendEmailConfirmationTo($user);

        \Session::flash('message', "Please confirm your email address. TODO" );

        unset ($this->redirectPath); // If I don't do that, we are redirected without flashed session from an obscure class

        return $user;
      }


    /**
    * Confirm a user's email address.
    *
    * @param  string $token
    * @return mixed
    */
    public function confirmEmail(Request $request, $token)
    {
      User::whereToken($token)->firstOrFail()->confirmEmail();
      $request->session()->flash('message', "Vous avez maintenant vérifié votre email" );
      return redirect('/');
    }

  }
