<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * This controller is the homepage and main entry point
 */
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
            if (Auth::user()->getPreference('show') == 'all') {
                // build a list of groups the user has access to
        if (Auth::user()->isAdmin()) { // super admin sees everything
          $groups = \App\Group::get()
          ->pluck('id');
        } else {
            $groups = \App\Group::public()
          ->get()
          ->pluck('id')
          ->merge(Auth::user()->groups()->pluck('groups.id'));
        }
            } else {
                $groups = Auth::user()->groups()->pluck('groups.id');
            }

            $discussions = \App\Discussion::with('userReadDiscussion', 'group', 'user')
      ->withCount('comments')
      ->whereIn('group_id', $groups)
      ->orderBy('updated_at', 'desc')->paginate(25);

            $actions = \App\Action::with('group')
      ->where('start', '>=', Carbon::now())
      ->whereIn('group_id', $groups)
      ->orderBy('start')
      ->paginate(10);


            return view('dashboard.homepage')
      ->with('tab', 'homepage')
      ->with('discussions', $discussions)
      ->with('actions', $actions);
        } else {
            return view('dashboard.presentation')
      ->with('tab', 'homepage');
        }
    }
}
