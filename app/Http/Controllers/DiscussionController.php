<?php

namespace App\Http\Controllers;

use Auth;
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
            // All the groups of a user : Auth::user()->groups()->pluck('groups.id')
            // All the public groups : \App\Group::public()

            // A merge of the two :

            //$groups = \App\Group::public()
            //->get()
            //->pluck('id')
            //->merge(Auth::user()->groups()->pluck('groups.id'));

            if (Auth::user()->getPreference('show') == 'all') {
                // build a list of groups the user has access to
                if (Auth::user()->isAdmin()) { // super admin sees everything
                    $groups = \App\Group::get()
                    ->pluck('id');
                } else { // normal user get public groups + groups he is member of
                    $groups = \App\Group::public()
                    ->get()
                    ->pluck('id')
                    ->merge(Auth::user()->groups()->pluck('groups.id'));
                }
            } else {
                $groups = Auth::user()->groups()->pluck('groups.id');
            }

            $discussions = \App\Discussion::with('userReadDiscussion', 'group', 'user', 'tags')
            ->withCount('comments')
            ->whereIn('group_id', $groups)
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            ->orderBy('updated_at', 'desc')->paginate(25);
        } else { // anon get public groups

            $groups = \App\Group::public()->get()->pluck('id');

            $discussions = \App\Discussion::with('group', 'user', 'tags')
            ->withCount('comments')
            ->whereIn('group_id', \App\Group::public()->get()->pluck('id'))
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            ->orderBy('updated_at', 'desc')->paginate(25);
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
