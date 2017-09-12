<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;

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
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }



    /**
     * Redirect the user to the OAuth Provider.
     *
     * @return Response
     */
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Obtain the user information from provider.  Check if the user already exists in our
     * database by looking up their provider_id in the database.
     * If the user exists, log them in. Otherwise, create a new user then log them in. After that
     * redirect them to the authenticated users homepage.
     *
     * @return Response
     */
    public function handleProviderCallback($provider)
    {
        $socialuser = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($socialuser, $provider);
        Auth::login($authUser, true);

        return redirect($this->redirectTo);
    }

    /**
     * If a user has registered before using social auth, return the user
     * else, create a new user object.
     *
     * @param  $user Socialite user object
     * @param $provider Social auth provider
     *
     * @return User
     */
    public function findOrCreateUser($socialuser, $provider)
    {
        $profile = \App\SocialProfile::where('provider_id', $socialuser->id)->where('provider', $provider)->first();

        // CASE 1 : we have a profile, so we have a user to return
        if ($profile) {
            return $profile->user()->first();
        }

        // Let's find a matching user from the email returned by the provider
        // TODO security : can we trust the email received from the provider?
        $user = \App\User::where('email', $socialuser->email)->first();

        // we have a matching user by email, so we create the profile then return the user
        if ($user) {
            $profile = \App\SocialProfile::create([
                'user_id'     => $user->id,
                'provider'    => $provider,
                'provider_id' => $socialuser->id,
            ]);

            return $user;
        }

        // we have nothing : we create a profile and a user

        $user = User::create([
            'name'     => $socialuser->name,
            'email'    => $socialuser->email,
        ]);

        $user->verified = 1; // TODO security implications of this. In short we trust another provider to check that an email is correct and verified. Fair enough?
        $user->save();

        $profile = \App\SocialProfile::create([
            'user_id'     => $user->id,
            'provider'    => $provider,
            'provider_id' => $socialuser->id,
        ]);

        return $user;
    }
}
