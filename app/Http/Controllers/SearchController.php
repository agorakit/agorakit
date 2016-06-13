<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use TeamTNT\TNTSearch\TNTSearch;

use Config;

class SearchController extends Controller
{


    public function index(Request $request){
        
        if ($request->has('query'))
        {
            $query = $request->get('query');

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
            ->orderBy('updated_at', 'desc')
            ->with('group')
            ->get();

            $actions = \App\Action::where('name', 'like', '%'. $query . '%')
            ->orWhere('body', 'like','%'. $query . '%')
            ->with('group')
            ->orderBy('updated_at', 'desc')
            ->get();


            $files = \App\File::where('name', 'like', '%'. $query . '%')
            ->with('group')
            ->orderBy('updated_at', 'desc')
            ->get();

            $comments = \App\Comment::where('body', 'like', '%'. $query . '%')
            ->with('discussion.group')
            ->orderBy('updated_at', 'desc')
            ->get();

            /*
            // this could be used for json stuff
            $results['groups'] = $groups;
            $results['users'] = $user;
            $results['discussions'] = $discussions;
            $results['files'] = $files;
            $results['comments'] = $comments;
            */


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
