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
                $tags = $tags->merge($group->getAllowedTags());
                $tags = $tags->merge($group->getTagsInUse());
            }
        }

        // Add all tags used on users
        $tags = $tags->merge(User::allTagModels());

        // filter and sort
        $tags = $tags->unique('normalized')->sortBy('normalized');


        return view('dashboard.tags-index')
            ->with('title', 'Tags')
            ->with('tags', $tags);
    }

    public function show(Request $request, Tag $tag)
    {
        if (Auth::check()) {
            $groups = Auth::user()->getVisibleGroups();
        } else {
            $groups = \App\Group::public()->pluck('id');
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
