<?php

namespace App\Http\Controllers;

use App\Models\File;
use App\Models\Group;
use Auth;
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
            $groups = \App\Models\Group::public()->get()->pluck('id');
        }

        $files = \App\Models\File::with('group', 'user', 'tags')
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            ->whereIn('group_id', $groups)
            ->orderBy('status', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(25);

        return view('dashboard.files')
            ->with('tags', $tags)
            ->with('tab', 'files')
            ->with('files', $files);
    }
}
