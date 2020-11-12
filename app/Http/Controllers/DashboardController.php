<?php

namespace App\Http\Controllers;

use App\Action;
use App\Discussion;
use App\File;
use App\Group;
use App\Traits\ContentStatus;
use Auth;
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
    }

    /**
     * Main HOMEPAGE.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (Auth::check()) {
            
            if (Auth::user()->getPreference('show', 'my') == 'admin') {
                // build a list of groups the user has access to
                if (Auth::user()->isAdmin()) { // super admin sees everything
                    $groups = Group::get()
                    ->pluck('id');
                } 
            } 

            if (Auth::user()->getPreference('show', 'my') == 'all') {
                    $groups = Group::public()
                    ->get()
                    ->pluck('id')
                    ->merge(Auth::user()->groups()->pluck('groups.id'));
            } 
            
            if (Auth::user()->getPreference('show', 'my') == 'my') {
                $groups = Auth::user()->groups()->pluck('groups.id');
            }

            $discussions = Discussion::with('userReadDiscussion', 'group', 'user', 'tags', 'comments')
            ->whereIn('group_id', $groups)
            ->where('status', '>=', ContentStatus::NORMAL)
            ->orderBy('updated_at', 'desc')
            ->take(20)
            ->get();

            $actions = Action::with('group', 'tags', 'attending', 'user')
            ->where('start', '>=', Carbon::now()->subDay())
            ->whereIn('group_id', $groups)
            ->orderBy('start')
            ->take(10)
            ->get();

            $files = File::with('group', 'user', 'tags')
            ->has('group')
            ->whereIn('group_id', $groups)
            ->where('status', '>=', ContentStatus::NORMAL)
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
