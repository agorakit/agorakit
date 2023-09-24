<?php

namespace App\Http\Controllers;

use Auth;
use App\File;
use App\Group;
use Illuminate\Http\Request;

/**
 * Global listing of files.
 */
class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('verified');
        $this->middleware('preferences');
    }

    /**
     * Show all the files independant of groups.
     */
    public function index(Request $request)
    {
        $tags = File::allTagModels()->sortBy('name');

        $tag = $request->get('tag');

        // generate a list of groups
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
        } else {
            $groups = \App\Group::public()->get()->pluck('id');
        }


        $files = \App\File::with('group', 'user', 'tags')
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            ->whereIn('group_id', $groups)
            ->orderBy('created_at', 'desc')
            ->paginate(25);
        


        return view('dashboard.files')
            ->with('tags', $tags)
            ->with('tab', 'files')
            ->with('files', $files);
    }
}
