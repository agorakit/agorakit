<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;
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
     * ?type : discussions / groups / actions / files / users
     * ?scope : my / all / admin
     * ?order : recent / old / big / small
     * 
     */
    public function index(Request $request)
    {
        // validate user input

        if ($request->get('query')) {
            $query = $request->get('query');
        }

        $type = '';
        if ($request->get('type')) {
            $type = $request->get('type');
        }

        if (!in_array($type, ['discussions', 'files', 'actions', 'groups', 'users', 'comments'])) {
            $type = 'discussions';
        }

        $scope = '';
        if ($request->get('scope')) {
            $scope = $request->get('scope');
        }

        if (!in_array($scope, ['my', 'all', 'admin'])) {
            $scope = Auth::user()->getPreference('show', 'my');
        }

        if ($scope == 'admin') {
            // build a list of groups the user has access to
            if (Auth::user()->isAdmin()) { // super admin sees everything
                $allowed_groups = Group::get()
                    ->pluck('id');
            }
        }

        if ($scope == 'all') {
            $allowed_groups = Group::public()
                ->get()
                ->pluck('id')
                ->merge(Auth::user()->groups()->pluck('groups.id'));
        }

        if ($scope == 'my') {
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
                $results = \App\User::with('groups')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }

            if ($type == 'discussions') {
                $results = \App\Discussion::whereIn('group_id', $allowed_groups)
                    ->with('group')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }


            if ($type == 'actions') {
                $results = \App\Action::whereIn('group_id', $allowed_groups)
                    ->with('group')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20, ['*'], 'actions');
            }

            if ($type == 'files') {
                $results = \App\File::whereIn('group_id', $allowed_groups)
                    ->with('group')
                    ->search($query)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);
            }

            if ($type == 'comments') {
                $results = \App\Comment::with('discussion', 'discussion.group')
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
