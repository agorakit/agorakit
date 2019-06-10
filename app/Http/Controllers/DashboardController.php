<?php

namespace App\Http\Controllers;

use Auth;
use App\Action;
use App\Discussion;
use App\File;
use App\Group;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
* This controller is the homepage and main entry point.
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
                    $groups = Group::get()
                    ->pluck('id');
                } else {
                    $groups = Group::public()
                    ->get()
                    ->pluck('id')
                    ->merge(Auth::user()->groups()->pluck('groups.id'));
                }
            } else {
                $groups = Auth::user()->groups()->pluck('groups.id');
            }

            $discussions = Discussion::with('userReadDiscussion', 'group', 'user')
            ->withCount('comments')
            ->whereIn('group_id', $groups)
            ->orderBy('updated_at', 'desc')
            ->take(25)
            ->get();

            $actions = Action::with('group')
            ->where('start', '>=', Carbon::now())
            ->whereIn('group_id', $groups)
            ->orderBy('start')
            ->take(10)
            ->get();

            $files = File::with('group')
            ->has('group')
            ->whereIn('group_id', $groups)
            ->orderBy('updated_at', 'desc')
            ->take(10)
            ->get();

            return view('dashboard.homepage')
            ->with('tab', 'homepage')
            ->with('discussions', $discussions)
            ->with('actions', $actions)
            ->with('files', $files);
        } else {
            return view('dashboard.presentation')
            ->with('tab', 'homepage');
        }
    }
}
