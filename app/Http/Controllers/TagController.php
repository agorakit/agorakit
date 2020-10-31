<?php

namespace App\Http\Controllers;

use App\Action;
use App\Discussion;
use App\File;
use App\Tag;
use App\User;
use App\Group;
use Auth;
use Illuminate\Http\Request;

/**
* This controller is used for quick tag editing on various models (discussions & files curently).
*/
class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('preferences');
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $tags = collect();

        if (Auth::user()->getPreference('show') == 'all') {
            $tags = Tag::all();
        } else {
            $groups = Auth::user()->groups()->get();
            // Add all allowed AND used tags from all requested groups
            foreach ($groups as $group) {
                $tags = $tags->merge($group->allowedTags());
                $tags = $tags->merge($group->tagsUsed());
            }
        }



        // Add all tags used on users
        $tags = $tags->merge(User::allTagModels());

        $tags = $tags->unique('normalized');

        $tags = $tags->sortBy('normalized');

        return view('dashboard.tags-index')
        ->with('title', 'Tags')
        ->with('tags', $tags);
    }

    public function show(Request $request, Tag $tag)
    {
        //$groups = Auth::user()->groups()->pluck('groups.id');

        if (Auth::user()->getPreference('show') == 'all') {
            // build a list of groups the user has access to
            if (Auth::user()->isAdmin()) { // super admin sees everything
                $groups = \App\Group::get()->pluck('id');
            } else { // normal user get public groups + groups he is member of
                $groups = \App\Group::public()->pluck('id')
                ->merge(Auth::user()->groups()->pluck('groups.id'));
            }
        } else {
            $groups = Auth::user()->groups()->pluck('groups.id');
        }

        $discussions = Discussion::whereHas('group', function ($q) use ($groups) {
            $q->whereIn('group_id', $groups);
        })
        ->whereHas('tags', function ($q) use ($tag) {
            $q->where('normalized', $tag->normalized);
        })
        ->get();

        $files = File::whereHas('group', function ($q) use ($groups) {
            $q->whereIn('group_id', $groups);
        })
        ->whereHas('tags', function ($q) use ($tag) {
            $q->where('normalized', $tag->normalized);
        })
        ->get();

        $actions = Action::whereHas('group', function ($q) use ($groups) {
            $q->whereIn('group_id', $groups);
        })
        ->whereHas('tags', function ($q) use ($tag) {
            $q->where('normalized', $tag->normalized);
        })
        ->get();

        $users = User::whereHas('groups', function ($q) use ($groups) {
            $q->whereIn('group_id', $groups);
        })
        ->whereHas('tags', function ($q) use ($tag) {
            $q->where('normalized', $tag->normalized);
        })
        ->get();

        $groups = Group::whereIn('id', $groups)
        ->whereHas('tags', function ($q) use ($tag) {
            $q->where('normalized', $tag->normalized);
        })
        ->get();

        return view('dashboard.tags-show')
        ->with('discussions', $discussions)
        ->with('files', $files)
        ->with('users', $users)
        ->with('actions', $actions)
        ->with('groups', $groups)
        ->with('tag', $tag)
        ->with('title', $tag->name);
    }
}
