<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;
use App\Discussion;
use App\User;
use App\Event;
use App\File;
use App\Comment;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth']);
    }

    /**
     * Parameters in the request : 
     * ?query : search terms
     * ?type : discussions / groups / events / files / users
     * ?scope : my / all / admin
     * ?order : recent / old / big / small
     * 
     */
    public function index(Request $request)
    {
        // validate user input

        if ($request->get('query')) {
            $query = mb_strimwidth($request->get('query'), 0, 32);
        }

        $type = '';
        if ($request->get('type')) {
            $type = $request->get('type');
        }

        if (!in_array($type, ['discussions', 'files', 'events', 'groups', 'users', 'comments'])) {
            $type = 'discussions';
        }

        $scope = '';
        if ($request->get('scope')) {
            $scope = $request->get('scope');
        }

        if (!in_array($scope, ['joined', 'all', 'admin'])) {
            $scope = Auth::user()->getPreference('show', 'joined');
        }

        if ($scope == 'admin') {
            // build a list of groups the user has access to
            if (Auth::user()->isAdmin()) { // super admin sees everything
                $allowed_groups = Group::pluck('id');
            }
        }

        if ($scope == 'all') {
            $allowed_groups = Group::public()
                ->pluck('id')
                ->merge(Auth::user()->groups()->pluck('groups.id'));
        }

        if ($scope == 'joined') {
            $allowed_groups = Auth::user()->groups()->pluck('groups.id');
        }



        // perform search
        if (isset($query)) {

            if ($type == 'groups') {
                $results = Group::whereIn('id', $allowed_groups)
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }


            if ($type == 'users') {
                $results = User::with('groups')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }

            if ($type == 'discussions') {
                $results = Discussion::whereIn('group_id', $allowed_groups)
                    ->with('group')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }


            if ($type == 'events') {
                $results = Event::whereIn('group_id', $allowed_groups)
                    ->with('group')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20, ['*'], 'events');
            }

            if ($type == 'files') {
                $results = File::whereIn('group_id', $allowed_groups)
                    ->with('group')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }

            if ($type == 'comments') {
                $results = Comment::with('discussion', 'discussion.group')
                    ->whereHas('discussion', function ($q) use ($allowed_groups) {
                        $q->whereIn('group_id', $allowed_groups);
                    })
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }
        }

        return view('search.results')
            ->with('results', $results)
            ->with('query', $query)
            ->with('scope', $scope)
            ->with('type', $type);
    }
}
