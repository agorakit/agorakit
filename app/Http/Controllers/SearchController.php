<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Config;

class SearchController extends Controller
{

    public function index(Request $request)
    {

        if ($request->has('query'))
        {
            $query = $request->get('query');

            // build a list of public groups and groups the user has access to
            $my_groups = Auth::user()->groups()->orderBy('name')->get();

            $my_groups_id = [];
            // using this array we can adjust the queries after to only include stuff the user has
            // might be a good idea to find a clever way to build this array of groups id :
            foreach ($my_groups as $the_group)
            {
                $my_groups_id[$the_group->id] = $the_group->id;
            }

            $public_groups = \App\Group::where('group_type', \App\Group::OPEN)->get();


            $public_groups_id = [];
            // using this array we can adjust the queries after to only include stuff the user has
            // might be a good idea to find a clever way to build this array of groups id :
            foreach ($public_groups as $the_group)
            {
                $public_groups_id[$the_group->id] = $the_group->id;
            }

            $allowed_groups = array_merge($my_groups_id,  $public_groups_id);




            $groups = \App\Group::where('name', 'like', '%'. $query . '%')
            ->orWhere('body', 'like', '%'. $query . '%')
            ->orderBy('name')
            ->get();

            $users = \App\User::where('name', 'like', '%'. $query . '%')
            ->orWhere('body', 'like','%'. $query . '%')
            ->orderBy('name')
            ->with('groups')
            ->get();

            $discussions = \App\Discussion::where('name', 'like', '%'. $query . '%')
            ->orWhere('body', 'like','%'. $query . '%')
            ->whereIn('group_id', $allowed_groups)
            ->orderBy('updated_at', 'desc')
            ->with('group')
            ->get();

            $actions = \App\Action::where('name', 'like', '%'. $query . '%')
            ->orWhere('body', 'like','%'. $query . '%')
            ->whereIn('group_id', $allowed_groups)
            ->with('group')
            ->orderBy('updated_at', 'desc')
            ->get();


            $files = \App\File::where('name', 'like', '%'. $query . '%')
            ->whereIn('group_id', $allowed_groups)
            ->with('group')
            ->orderBy('updated_at', 'desc')
            ->get();

            $comments = \App\Comment::where('body', 'like', '%'. $query . '%')
            ->with('discussion.group') // TODO we'd better remove comments form search if the discussion they belong to is not from a public group
            ->orderBy('updated_at', 'desc')
            ->get();



            // set in advance which tab will be active on the search results page
            $groups->class='';
            $discussions->class='';
            $actions->class='';
            $users->class='';
            $comments->class='';
            $files->class='';

            // the order of those ifs should match the order of the tabs on the results view :-)
            if ($groups->count() > 0)
            {
                $groups->class='active';
            }
            elseif ($discussions->count() > 0)
            {
                $discussions->class='active';
            }
            elseif ($actions->count() > 0)
            {
                $action->class='active';
            }
            elseif ($users->count() > 0)
            {
                $users->class='active';
            }
            elseif ($comments->count() > 0)
            {
                $comments->class='active';
            }
            elseif ($files->count() > 0)
            {
                $files->class='active';
            }


            return view('search.results')
            ->with('groups', $groups)
            ->with('users', $users)
            ->with('discussions', $discussions)
            ->with('files', $files)
            ->with('comments', $comments)
            ->with('actions', $actions)
            ->with('query', $query);

        }

    }
}
