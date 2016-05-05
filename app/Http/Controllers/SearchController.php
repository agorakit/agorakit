<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use TeamTNT\TNTSearch\TNTSearch;

use Config;

class SearchController extends Controller
{


    public function index(Request $request){

        /*
        $tnt = new TNTSearch;

        // Get current dbconfig
        $dbConfig = array_merge(Config::get('database.connections.' . Config::get('database.default')));

        $dbConfig['storage'] = storage_path('app/');

        $tnt->loadConfig($dbConfig);

        $tnt->selectIndex("name.index");

        $tnt->asYouType = true;

        $results = $tnt->search($request->get('query'), 10);
        */

        $query = $request->get('query');

        $groups = \App\Group::where('name', 'like', '%'. $query . '%')
        ->orWhere('body', 'like', '%'. $query . '%')
        ->get();

        $users = \App\User::where('name', 'like', '%'. $query . '%')
        ->orWhere('body', 'like','%'. $query . '%')
        ->get();

        $discussions = \App\Discussion::where('name', 'like', '%'. $query . '%')
        ->orWhere('body', 'like','%'. $query . '%')
        ->get();


        $files = \App\File::where('name', 'like', '%'. $query . '%')
        ->get();

        $comments = \App\Comment::where('body', 'like', '%'. $query . '%')
        ->get();



        /*
        $results['groups'] = $groups;
        $results['users'] = $user;
        $results['discussions'] = $discussions;
        $results['files'] = $files;
        $results['comments'] = $comments;
        */

        return view('search.results')
        ->with('groups', $groups)
        ->with('users', $users)
        ->with('discussions', $discussions)
        ->with('files', $files)
        ->with('comments', $comments);

    }
}
