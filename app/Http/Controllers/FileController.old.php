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

        if (Auth::check()) {
            $groups = Auth::user()->getVisibleGroups();
        } else {
            $groups = Group::public()->pluck('id');
        }


        $files = File::with('group', 'user', 'tags')
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
