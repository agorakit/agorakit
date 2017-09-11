<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mailers\AppMailer;
use App\User;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Http\Request;
use Socialite;
use Validator;

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
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => ['logout', 'confirmEmail']]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     *
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     *
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
        flash()->info(trans('messages.you_have_verified_your_email'));

        // add user to the group (s)he has been invited to before registering //TODO : good idea ?
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
