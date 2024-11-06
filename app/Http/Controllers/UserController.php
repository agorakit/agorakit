<?php

namespace App\Http\Controllers;

use App\Mail\ContactUser;
use App\Mail\UserConfirmation;
use App\User;
use App\Group;
use Auth;
use Gate;
use Illuminate\Http\Request;
use Image;
use Mail;
use Redirect;
use Storage;
use Illuminate\Support\Facades\Hash;
use \Cviebrock\EloquentSluggable\Services\SlugService;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('preferences');
        $this->middleware('verified', ['except' => 'sendVerificationAgain']);
        $this->middleware('throttle:2,1', ['only' => ['mail', 'sendVerificationAgain']]); // 2 emails per  minute should be enough for non bots
    }

    public function index(Request $request)
    {
        $title = trans('messages.users');
        if (Auth::check()) {
            if (Auth::user()->getPreference('show') == 'all') {
                // build a list of groups the user has access to
                if (Auth::user()->isAdmin()) { // super admin sees everything
                    $groups = Group::pluck('id');
                } else { // normal user get public groups + groups he is member of
                    $groups = Group::public()
                        ->pluck('id')
                        ->merge(Auth::user()->groups()->pluck('groups.id'));
                }
            } else {
                // show only "my" group
                $groups = Auth::user()->groups()->pluck('groups.id');
            }

            // Magic query to get all the users who have one of the groups defined above in their membership table
            $users = User::whereHas('groups', function ($q) use ($groups) {
                $q->whereIn('group_id', $groups);
            })
                ->where('verified', 1)
                ->orderBy('created_at', 'desc');

            if ($request->has('search')) {
                $users = $users->search($request->get('search'));
            }


            $users = $users->paginate(20);

            return view('dashboard.users')
                ->with('tab', 'users')
                ->with('users', $users)
                ->with('title', $title);
        } else {
            $users = User::where('verified', 1)
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return view('dashboard.users')
                ->with('tab', 'users')
                ->with('users', $users)
                ->with('title', $title);
        }
    }

    /**
     * Show contact form for the user.
     */
    public function contactForm(User $user)
    {
        if ($user->isVerified()) {
            return view('users.contact')
                ->with('tab', 'contact')
                ->with('user', $user);
        } else {
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
            } else {
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
     */
    public function show(User $user)
    {
        if ($user->isVerified() || Auth::user()->isAdmin()) {
            $title = $user->username . ' ' . trans('messages.user_profile');

            return view('users.show')
                ->with('activities', $user->activities()->whereIn('group_id', Group::public()->pluck('id'))->paginate(10))
                ->with('user', $user)
                ->with('tab', 'profile')
                ->with('title', $title);
        }

        flash(__('This user is unverified'));

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (Gate::allows('update', $user)) {
            return view('users.edit')
                ->with('allowedTags', $user->getAllowedTags())
                ->with('newTagsAllowed', $user->areNewTagsAllowed())
                ->with('selectedTags', $user->getSelectedTags())
                ->with('user', $user)
                ->with('tab', 'edit');
        } else {
            abort(403);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        if (Gate::allows('update', $user)) {
            $user->name = $request->input('name');

            $previous_email = $user->email;
            $user->email = $request->input('email');
            $user->body = $request->input('body');


            if ($user->address != $request->input('address')) {
                // we need to update user address and geocode it
                $user->address = $request->input('address');
                if (!$user->geocode()) {
                    warning(trans('messages.address_cannot_be_geocoded'));
                } else {
                    flash(trans('messages.ressource_geocoded_successfully'));
                }
            }

            // handle username change
            if ($user->username != $request->input('username')) {
                $existing_user = User::where('username', $request->input('username'))->first();
                if ($existing_user) {
                    warning(trans('This username is taken, another one has been generated'));
                    $user->username = SlugService::createSlug(User::class, 'username', $request->input('username'));
                } else {
                    $user->username = $request->input('username');
                }
            }




            // handle tags
            if ($request->get('tags')) {
                $user->retag($request->get('tags'));
            } else {
                $user->detag();
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
                $user->setCoverFromRequest($request);
            }

            // handle email change : if a user changes his email, we set him/her to unverified, and send a new verification email
            if ($previous_email != $user->email) {
                $user->verified = 0;
                $user->token = str_random(30);
                Mail::to($user)->send(new UserConfirmation($user));
            }

            // handle password change
            if ($request->input('password')) {
                // validate password
                $request->validate([
                    'password' => ['required', 'string', 'min:8', 'confirmed'],
                ]);

                $user->password = Hash::make($request->input('password'));
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
     */
    public function sendVerificationAgain(Request $request, User $user)
    {
        if ($user->verified == 0) {
            Mail::to($user)->send(new UserConfirmation($user));
            flash(trans('messages.invitation_sent_again'));

            return redirect()->route('users.show', $user);
        } else {
            abort(404, 'Your account is already verified');
        }
    }

    /**
     * Remove the specified resource from storage.
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
            if ($user->email == 'anonymous@agorakit.org') {
                abort(500, 'Do not delete anonymous user, you fool :-)');
            }

            $anonymous = \App\User::getAnonymousUser();

            if (is_null($anonymous)) {
                abort(500, 'Can\'t load the anonymous user model, please run all migrations to create the anynmous special system user');
            }

            $message = [];

            // First case assign all to anonymous :
            if ($request->content == 'anonymous') {
                $message[] = $user->comments->count() . ' comments anonymized';
                foreach ($user->comments as $comment) {
                    $comment->timestamps = false;
                    $comment->user()->associate($anonymous);
                    $comment->save();
                }

                $message[] = $user->discussions->count() . ' discussions anonymized';
                foreach ($user->discussions as $discussion) {
                    $discussion->timestamps = false;
                    $discussion->user()->associate($anonymous);
                    $discussion->save();
                }

                $message[] = $user->files->count() . ' files anonymized';
                foreach ($user->files as $file) {
                    $file->timestamps = false;
                    $file->user()->associate($anonymous);
                    $file->save();
                }

                $message[] = $user->actions->count() . ' actions anonymized';
                foreach ($user->actions as $action) {
                    $action->timestamps = false;
                    $action->user()->associate($anonymous);
                    $action->save();
                }

                $message[] = $user->memberships->count() . ' memberships deleted';
                $user->memberships()->delete();
            }

            // Second case delete all :
            if ($request->content == 'delete') {
                $message[] = $user->comments->count() . ' comments deleted';
                foreach ($user->comments as $comment) {
                    $comment->timestamps = false;
                    $comment->delete();
                }

                $message[] = $user->discussions->count() . ' discussions deleted';
                foreach ($user->discussions as $discussion) {
                    $discussion->timestamps = false;

                    if ($discussion->comments->count() > 0) {
                        $discussion->user()->associate($anonymous);
                        $discussion->save();
                    } else {
                        $discussion->delete();
                    }
                }

                $message[] = $user->files->count() . ' files deleted';
                foreach ($user->files as $file) {
                    $file->timestamps = false;
                    $file->delete();
                }

                $message[] = $user->actions->count() . ' actions deleted';
                foreach ($user->actions as $action) {
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
            foreach ($message as $txt) {
                flash($txt);
            }

            Auth::logout();

            return redirect()->route('index');
        }
    }
}
