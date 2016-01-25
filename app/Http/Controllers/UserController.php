<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Mail;
use Gate;
use Storage;
use File;
use Image;
use App\Mailers\AppMailer;
use App\Group;

class UserController extends Controller {


  public function __construct()
  {
    $this->middleware('cache', ['only' => ['index', 'show']]);
    $this->middleware('verified', ['only' => ['contact', 'mail']]);
    $this->middleware('throttle:2,1', ['only' => ['mail']]); // 2 emails per  minute should be enough for non bots
  }

  /**
  * Display a listing of the resource.
  *
  * @return Response
  */
  public function index($group_id)
  {
    $group = \App\Group::with('users')->findOrFail($group_id);
    $users = $group->users()->orderBy('name', 'asc')->paginate(25);

    return view('users.index')
    ->with('users', $users)
    ->with('group', $group)
    ->with('tab', 'users');
  }



  /**
  * Show contact form for the user
  */
  public function contact($user_id)
  {
    $user = \App\User::findOrFail($user_id);
    return view('users.contact')
    ->with('tab', 'contact')
    ->with('user', $user);
  }

  /**
  * Mails the user
  */
  public function mail($user_id, Request $request)
  {
    $to_user = \App\User::findOrFail($user_id);
    $from_user = Auth::user();

    /*
    $requestsPerHour = 5;
    $key = 'contact_user_' . $to_user->id . '_from_user_' . $from_user->id;

    // Add if doesn't exist
    // Remember for 1 hour
    \Cache::add($key, 0, 60);

    // Add to count
    $count = \Cache::increment($key);
    */

    //if( $count > $requestsPerHour )
    //{
    //  $request->session()->flash('message', trans('messages.message_not_sent_too_many_per_hour'));
    //}
    //else
    //{
    if ($request->has('body'))
    {
      $body = $request->input('body');

      Mail::send('emails.contact', ['to_user' => $to_user, 'from_user' => $from_user, 'body' => $body ], function ($message) use ($to_user, $from_user) {
        $message->from($from_user->email, $from_user->name)
        ->to($to_user->email, $to_user->name)
        ->subject('[' . env('APP_NAME') . '] ' . trans('messages.a_message_for_you'));
      });

      $request->session()->flash('message', trans('messages.message_sent'));
    }
    //}
    return redirect()->action('UserController@show', $to_user->id);
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return Response
  */
  public function create()
  {

  }

  /**
  * Store a newly created resource in storage.
  *
  * @return Response
  */
  public function store()
  {

  }

  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function show($id)
  {
    $user = \App\User::findOrFail($id);
    return view('users.show')->with('user', $user)->with('tab', 'profile');
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return Response
  */
  public function edit($id)
  {
    $user = \App\User::findOrFail($id);
    if (Gate::allows('update', $user))
    {
      return view('users.edit')
      ->with('user', $user)->with('tab', 'edit');
    }
    else
    {
      abort(403);
    }
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  int  $id
  * @return Response
  */
  public function update(Request $request, $id)
  {
    $user = \App\User::findOrFail($id);

    if (Gate::allows('update', $user))
    {
      $user->name = $request->input('name');

      $previous_email = $user->email;
      $user->email = $request->input('email');
      $user->body = $request->input('body');


      // validation
      if ($user->isInvalid()) {
        // Oops.
        return redirect()->action('UserController@edit', $user->id)
        ->withErrors($user->getErrors())
        ->withInput();
      }

      // handle cover
      if ($request->hasFile('cover'))
      {
        Storage::disk('local')->makeDirectory('users/' . $user->id);
        Image::make($request->file('cover'))->widen(500)->save(storage_path() . '/app/users/' . $user->id . '/cover.jpg');
        Image::make($request->file('cover'))->fit(128,128)->save(storage_path() . '/app/users/' . $user->id . '/thumbnail.jpg');
      }

      // handle email change : if a user changes his email, we set him/her to unverified, and send a new verification email
      if ($previous_email <> $user->email)
      {
        $user->verified = 0;
        $user->token = str_random(30);
        $mailer = new AppMailer;
        $mailer->sendEmailConfirmationTo($user);
      }

      $user->save();

      $request->session()->flash('message', trans('messages.ressource_updated_successfully'));

      return redirect()->action('UserController@show', [$user->id]);
    }
    else
    {
      abort(403);
    }

  }

  /**
  * Send verification token to a user, again, for example if it's stuck in spam or wathever else event. Probably needs throttling TODO
  * @param  Request $request
  * @param  Int  $id      User id
  * @return Flash message and returns to homepage
  */
  public function sendVerificationAgain(Request $request, $id)
  {
    $user = \App\User::findOrFail($id);
    if ($user->verified == 0)
    {
      $mailer = new AppMailer;
      $mailer->sendEmailConfirmationTo($user);
      $request->session()->flash('message', trans('messages.invitation_sent_again'));
      return redirect()->action('UserController@show', [$user->id]);
    }
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return Response
  */
  public function destroy($id)
  {

  }

  public function cover($id)
  {
    $path = storage_path() . '/app/users/' . $id . '/cover.jpg';

    if (File::exists($path))
    {
      $cachedImage = Image::cache(function($img) use ($path) {
        return $img->make($path)->fit(300, 200);
      }, 60000, true);

      return $cachedImage->response();

    }
    else
    {
      return Image::canvas(300,200)->fill('#cccccc')->response(); // TODO caching or default group image instead
      abort(404);
    }
  }



  public function avatar($id)
  {
    $path = storage_path() . '/app/users/' . $id . '/cover.jpg';

    if (File::exists($path))
    {
      $cachedImage = Image::cache(function($img) use ($path) {
        return $img->make($path)->fit(128, 128);
      }, 60000, true);

      return $cachedImage->response();

    }
    else
    {
      return Image::canvas(128,128)->fill('#cccccc')->response(); // TODO caching or default group image instead
      abort(404);
    }
  }


}
