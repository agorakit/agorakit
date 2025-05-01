<?php

namespace App\Http\Controllers;

use App\Event;
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

    public function index(Request $request)
    {
        if (Auth::check()) {
            return redirect()->route('discussions');
        } else {
            return redirect()->route('presentation');
        }
    }

    /**
     * Main HOMEPAGE.
     *
     * @return Response
     */
    public function presentation(Request $request)
    {
        if (Auth::check()) {
            $groups = $request->user()->groups();
            $groups = $groups->with('tags', 'users', 'events', 'discussions')
            ->orderBy('status', 'desc')
            ->orderBy('updated_at', 'desc');
            $groups = $groups->simplePaginate(20)->appends(request()->query());
        } else {

            $groups = new Group();
            $groups = $groups->notSecret();

            $groups = $groups->with('tags', 'users', 'events', 'discussions')
                ->orderBy('status', 'desc')
                ->orderBy('updated_at', 'desc');

            $groups = $groups->paginate(20)->appends(request()->query());
        }

        return view('dashboard.presentation')
            ->with('groups', $groups)
            ->with('tab', 'homepage');
    }
}
