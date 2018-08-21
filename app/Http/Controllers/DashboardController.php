<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
  public function __construct()
  {
    $this->middleware('verified', ['only' => ['users', 'files', 'activities']]);
    $this->middleware('preferences');
  }

  /**
  * Main HOMEPAGE.
  *
  * @return Response
  */
  public function index(Request $request)
  {
    if (Auth::check()) {
      $my_groups = Auth::user()->groups()->get();

      // other groups are public and not groups the user is member of
      $other_groups = \App\Group::publicgroups()->whereNotIn('id', $my_groups->pluck('id'))->get();

      // if user is not subscribed to any group, we redirect to group list homepage instead.
      if ($my_groups->count() == 0) {
        flash(trans('messages.join_a_group'));
      }

      $my_discussions = \App\Discussion::with('userReadDiscussion', 'user', 'group', 'tags')
      ->whereIn('group_id', $my_groups->pluck('id'))
      ->orderBy('updated_at', 'desc')->take(10)->get();

      $my_actions = \App\Action::with('user', 'group')
      ->whereIn('group_id', $my_groups->pluck('id'))
      ->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->take(5)->get();

      $other_discussions = \App\Discussion::with('userReadDiscussion', 'user', 'group', 'tags')
      ->whereIn('group_id', $other_groups->pluck('id'))
      ->orderBy('updated_at', 'desc')->take(10)->get();

      $other_actions = \App\Action::with('user', 'group')
      ->whereIn('group_id', $other_groups->pluck('id'))
      ->where('start', '>=', Carbon::now())->orderBy('start', 'asc')->take(5)->get();



      return view('dashboard.homepage')
      ->with('tab', 'homepage')
      ->with('my_groups', $my_groups)
      ->with('my_discussions', $my_discussions)
      ->with('my_actions', $my_actions)
      ->with('other_actions', $other_actions)
      ->with('other_discussions', $other_discussions);
    } else {
      return view('dashboard.presentation')
      ->with('tab', 'homepage');
    }
  }

  public function users()
  {
    $users = \App\User::with('groups')->where('verified', 1)->orderBy('created_at', 'desc')->paginate(20);

    return view('dashboard.users')
    ->with('tab', 'users')
    ->with('users', $users);
  }







}
