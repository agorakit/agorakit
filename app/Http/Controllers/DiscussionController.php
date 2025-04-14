<?php

namespace App\Http\Controllers;

use Auth;
use App\Group;
use Illuminate\Http\Request;
use Context;

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
        $groups = Context::getVisibleGroups();
        $discussions = \App\Discussion::with('userReadDiscussion', 'group', 'user', 'tags', 'comments')
            ->withCount('comments')
            ->whereIn('group_id', $groups)
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            })
            // ->orderBy('status', 'desc') // don't show pinned discussions first in overview imvho
            ->orderBy('updated_at', 'desc')
            ->paginate(25);


        $tags = \App\Discussion::allTags();
        natcasesort($tags);

        return view('dashboard.discussions')
            ->with('title', trans('messages.discussions'))
            ->with('tab', 'discussions')
            ->with('discussions', $discussions)
            ->with('tags', $tags);
    }
}
