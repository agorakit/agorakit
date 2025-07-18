<?php

namespace Agorakit\Http\Controllers;

use Auth;
use Agorakit\File;
use Agorakit\Group;
use Illuminate\Http\Request;
use Context;

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

        $groups = Context::getVisibleGroups();

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
