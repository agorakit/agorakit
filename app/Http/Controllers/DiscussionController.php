<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;
use Illuminate\Http\Request;

/**
 * This controller generates a global list of discussions with unread count (independant of groups).
 */
class DiscussionController extends Controller
{
    public function __construct()
    {
        $this->middleware('preferences');
    }

    public function index(Request $request)
    {
        $tag = $request->get('tag');

        // define a list fo groups the user has access to // TODO generalize this somewhere else
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

            $discussions = \App\Discussion::with('userReadDiscussion', 'group', 'user', 'tags', 'comments')
            ->withCount('comments')
            ->whereIn('group_id', $groups)
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            // ->orderBy('status', 'desc') // don't show pinned discussions first in overview imvho
            ->orderBy('updated_at', 'desc')
            ->paginate(25);
        } else { // anon get public groups

            $groups = \App\Group::public()->get()->pluck('id');

            $discussions = \App\Discussion::with('group', 'user', 'tags')
            ->withCount('comments')
            ->whereIn('group_id', \App\Group::public()->get()->pluck('id'))
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            ->orderBy('status', 'desc')
            ->orderBy('updated_at', 'desc')
            ->paginate(25);
        }

        $tags = \App\Discussion::allTags();
        natcasesort($tags);

        return view('dashboard.discussions')
        ->with('title', trans('messages.discussions'))
        ->with('tab', 'discussions')
        ->with('discussions', $discussions)
        ->with('tags', $tags);
    }
}
