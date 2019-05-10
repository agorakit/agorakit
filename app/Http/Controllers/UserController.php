<?php

namespace App\Http\Controllers;

use App\Group;
use App\Mailers\AppMailer;
use App\User;
use DB;
use Auth;
use File;
use Gate;
use Illuminate\Http\Request;
use Image;
use Mail;
use Redirect;
use Storage;
use App\Mail\UserConfirmation;

use App\Mail\ContactUser;

class UserController extends Controller
{
  public function __construct()
  {
    $this->middleware('preferences');
    $this->middleware('cache', ['only' => ['cover', 'avatar']]);
    $this->middleware('verified', ['only' => ['contact', 'contactForm']]);
    $this->middleware('throttle:2,1', ['only' => ['mail', 'sendVerificationAgain']]); // 2 emails per  minute should be enough for non bots
  }


  public function index()
  {


    if (Auth::check()) {

      if (Auth::user()->getPreference('show') == 'all') {
        // build a list of groups the user has access to
        if (Auth::user()->isAdmin()) { // super admin sees everything
          $groups = \App\Group::get()
          ->pluck('id');
        } else { // normal user get public groups + groups he is member of
          $groups = \App\Group::public()
          ->get()
          ->pluck('id')
          ->merge(Auth::user()->groups()->pluck('groups.id'));
        }
      } else {
        // show only "my" group
        $groups = Auth::user()->groups()->pluck('groups.id');
      }



      $users = User::whereHas('groups', function($q) use ($groups) {
        $q->whereIn('group_id', $groups);
      })
      ->where('verified', 1)
      ->orderBy('created_at', 'desc')
      ->paginate(20);


      return view('dashboard.users')
      ->with('tab', 'users')
      ->with('users', $users);
    }

    else {
      $users = User::where('verified', 1)
      ->orderBy('created_at', 'desc')
      ->paginate(20);


      return view('dashboard.users')
      ->with('tab', 'users')
      ->with('users', $users);

    }

  }

  /**
  * Show contact form for the user.
  */
  public function contactForm(User $user)
  {
    if ($user->isVerified())
    {
      return view('users.contact')
      ->with('tab', 'contact')
      ->with('user', $user);
    }
    else {
      flash(__('This user did not verify his/her email so you cannot contact him/her'));
      return redirect()->back();
    }
  }

  /**
  * Mails the user.
  */
  public function contact(User $user, Request $request)
  {
    $from_user = Auth::user();
    $to_user = $user;


    if ($to_user->verified == 1) {
      if ($request->has('body')) {
        $body = $request->input('body');
        Mail::to($to_user)->send(new ContactUser($from_user, $to_user, $body, $request->has('reveal_email')));

        flash(trans('messages.message_sent'));
        return redirect()->route('users.contactform', $to_user);
      }
      else {
        flash(__('Please type a message'));
        return redirect()->back();
      }


    } else {
      flash(__('The user you are trying to contact did not verify his/her email'));
      return redirect()->back();
    }
  }

  /**
  * Display the specified resource.
  *
  * @param int $id
  *
  * @return Response
  */
  public function show(User $user)
  {
    return view('users.show')
    ->with('activities', $user->activities()->whereIn('group_id', \App\Group::public()->get()->pluck('id'))->paginate(10))
    ->with('user', $user)
    ->with('tab', 'profile');
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param int $id
  *
  * @return Response
  */
  public function edit(User $user)
  {
    if (Gate::allows('update', $user)) {
      return view('users.edit')
      ->with('user', $user)->with('tab', 'edit');
    } else {
      abort(403);
    }
  }

  /**
  * Update the specified resource in storage.
  *
  * @param int $id
  *
  * @return Response
  */
  public function update(Request $request, User $user)
  {
    if (Gate::allows('update', $user)) {
      $user->name = $request->input('name');

      $previous_email = $user->email;
      $user->email = $request->input('email');
      $user->body = $request->input('body');
      $user->username = $request->input('username');

      if ($user->address != $request->input('address')) {
        // we need to update user address and geocode it
        $user->address = $request->input('address');
        if (!$user->geocode()) {
          flash(trans('messages.address_cannot_be_geocoded'));
        } else {
          flash(trans('messages.ressource_geocoded_successfully'));
        }
      }

      // handle the case the edit form is used to make a user an admin (or remove admin right)
      if (Auth::user()->isAdmin()) {
        if ($request->get('is_admin') == 'yes') {
          $user->admin = 1;
        }

        if ($request->get('is_admin') == 'no') {
          $user->admin = 0;
        }

        if ($request->get('is_verified') == 'yes') {
          $user->verified = 1;
        }

        if ($request->get('is_verified') == 'no') {
          $user->verified = 0;
        }
      }

      // validation
      if ($user->isInvalid()) {
        // Oops.
        return redirect()->route('users.edit', $user)
        ->withErrors($user->getErrors())
        ->withInput();
      }

      // handle cover
      if ($request->hasFile('cover')) {
        Storage::disk('local')->makeDirectory('users/'.$user->id);
        Image::make($request->file('cover'))->widen(800)->save(storage_path().'/app/users/'.$user->id.'/cover.jpg');
      }

      // handle email change : if a user changes his email, we set him/her to unverified, and send a new verification email
      if ($previous_email != $user->email) {
        $user->verified = 0;
        $user->token = str_random(30);
        Mail::to($user)->send(new UserConfirmation($user));
      }

      $user->save();

      flash(trans('messages.ressource_updated_successfully'));

      return redirect()->route('users.show', $user);
    } else {
      abort(403);
    }
  }

  /**
  * Send verification token to a user, again, for example if it's stuck in spam or wathever else event.
  *
  * @param Request $request
  * @param int     $id      User id
  *
  * @return Flash message and returns to homepage
  */
  public function sendVerificationAgain(Request $request, User $user)
  {
    if ($user->verified == 0) {
      Mail::to($user)->send(new UserConfirmation($user));
      flash(trans('messages.invitation_sent_again'));

      return redirect()->route('users.show', $user);
    }
    else
    {
      abort(404, 'Your account is already verified');
    }
  }



  /**
  * Remove the specified resource from storage.
  *
  * @param int $id
  *
  * @return Response
  */
  public function destroy(User $user, Request $request)
  {
    $this->authorize('delete', $user);

    // Show a form to decide what do to:
    if ($request->isMethod('get')) {

      return view('users.delete')->with('user', $user);
    }

    // Do the deletion:
    if ($request->isMethod('delete')) {


      if ($user->email == 'anonymous@agorakit.org')
      {
        abort(500, 'Do not delete anonymous user, you fool :-)');
      }

      $anonymous = \App\User::getAnonymousUser();

      if (is_null($anonymous)) {
        abort(500, 'Can\'t load the anonymous user model, please run all migrations to create the anynmous special system user');
      }

      $message = array();

      // First case assign all to anonymous :
      if ($request->content == 'anonymous')
      {
        $message[] = $user->comments->count() . ' comments anonymized';
        foreach ($user->comments as $comment)
        {
          $comment->timestamps = false;
          $comment->user()->associate($anonymous);
          $comment->save();

        }

        $message[] = $user->discussions->count() . ' discussions anonymized';
        foreach ($user->discussions as $discussion)
        {
          $discussion->timestamps = false;
          $discussion->user()->associate($anonymous);
          $discussion->save();
        }

        $message[] = $user->files->count() . ' files anonymized';
        foreach ($user->files as $file)
        {
          $file->timestamps = false;
          $file->user()->associate($anonymous);
          $file->save();
        }

        $message[] = $user->actions->count() . ' actions anonymized';
        foreach ($user->actions as $action)
        {
          $action->timestamps = false;
          $action->user()->associate($anonymous);
          $action->save();
        }

        $message[] = $user->memberships->count() . ' memberships deleted';
        $user->memberships()->delete();
      }



      // Second case delete all :
      if ($request->content == 'delete')
      {
        $message[] = $user->comments->count() . ' comments deleted';
        foreach ($user->comments as $comment)
        {
          $comment->timestamps = false;
          $comment->delete();
        }


        $message[] = $user->discussions->count() . ' discussions deleted';
        foreach ($user->discussions as $discussion)
        {
          $discussion->timestamps = false;

          if ($discussion->comments->count() > 0)
          {
            $discussion->user()->associate($anonymous);
            $discussion->save();
          }
          else
          {
            $discussion->delete();
          }
        }

        $message[] = $user->files->count() . ' files deleted';
        foreach ($user->files as $file)
        {
          $file->timestamps = false;
          $file->delete();
        }

        $message[] = $user->actions->count() . ' actions deleted';
        foreach ($user->actions as $action)
        {
          $action->timestamps = false;
          $action->delete();
        }

        $message[] = $user->memberships->count() . ' memberships deleted';
        $user->memberships()->delete();

      }


      // finaly delete user account
      $user->delete();
      $message[] = 'User deleted';

      // flash all info
      foreach ($message as $txt)
      {
        flash($txt);
      }


      return redirect()->route('index', $user);

    }





  }

  public function cover(User $user)
  {
    $path = storage_path().'/app/users/'.$user->id.'/cover.jpg';

    if (File::exists($path)) {
      $cachedImage = Image::cache(function ($img) use ($path) {
        return $img->make($path)->fit(400, 400);
      }, 60000, true);

      return $cachedImage->response();
    } else {
      return redirect(url('/images/avatar.jpg'));
    }
  }

  public function avatar(User $user)
  {
    $path = storage_path().'/app/users/'.$user->id.'/cover.jpg';

    if (File::exists($path)) {
      $cachedImage = Image::cache(function ($img) use ($path) {
        return $img->make($path)->fit(128, 128);
      }, 60000, true);

      return $cachedImage->response();
    } else {
      return redirect(url('/images/avatar.jpg'));
    }
  }
}
