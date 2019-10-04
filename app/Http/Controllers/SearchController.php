<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['verified', 'auth']);
    }

    public function index(Request $request)
    {
        if ($request->get('query')) {
            $query = $request->get('query');

            // Build a list of groups the user has access to. Those are public groups + groups the user is member of.
            //$allowed_groups = \App\Group::open()->get()->pluck('id')->merge(Auth::user()->groups()->pluck('groups.id'));

            // let's keep only the groups a user is member of for now.
            $allowed_groups = Auth::user()->groups()->pluck('groups.id');

            $groups = \App\Group::whereIn('id', $allowed_groups)
            ->search($query)
            ->get();

            $users = \App\User::with('groups')
            ->search($query)
            ->get();

            $discussions = \App\Discussion::whereIn('group_id', $allowed_groups)
            ->with('group')
            ->search($query)
            ->get();

            $actions = \App\Action::whereIn('group_id', $allowed_groups)
            ->with('group')
            ->search($query)
            ->get();

            $files = \App\File::whereIn('group_id', $allowed_groups)
            ->with('group')
            ->search($query)
            ->get();

            $comments = \App\Comment::with('discussion', 'discussion.group')
            ->whereHas('discussion', function ($q) use ($allowed_groups) {
                $q->whereIn('group_id', $allowed_groups);
            })
            ->search($query)
            ->get();

            // set in advance which tab will be active on the search results page
            $groups->class = '';
            $discussions->class = '';
            $actions->class = '';
            $users->class = '';
            $comments->class = '';
            $files->class = '';

            // the order of those ifs should match the order of the tabs on the results view :-)
            if ($groups->count() > 0) {
                $groups->class = 'active';
            } elseif ($discussions->count() > 0) {
                $discussions->class = 'active';
            } elseif ($actions->count() > 0) {
                $actions->class = 'active';
            } elseif ($users->count() > 0) {
                $users->class = 'active';
            } elseif ($comments->count() > 0) {
                $comments->class = 'active';
            } elseif ($files->count() > 0) {
                $files->class = 'active';
            }

            return view('search.results')
            ->with('groups', $groups)
            ->with('users', $users)
            ->with('discussions', $discussions)
            ->with('files', $files)
            ->with('comments', $comments)
            ->with('actions', $actions)
            ->with('query', $query);
        } else {
            return redirect()->back();
        }
    }
}
