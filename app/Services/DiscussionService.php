<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Group;
use App\Discussion;
use Auth;

class DiscussionService
{
    public function load(Request $request, Group $group)
    {
        // First define the groups we want to show discussions for, groups id's will be stored in the $groups array
        // If we have a group in the url, easy, show discussions belonging to this group
        if ($group->exists) {
            $this->authorize('view-discussions', $group);
            $groups[] = $group->id;
            $context = 'group';
        }
        // If not we need to show some kind of overview
        else {
            if (Auth::check()) {
                // user is logged in, we show according to preferences
                $groups = Auth::user()->getVisibleGroups();
            } else {
                // anonymous users get all public groups
                $groups = Group::public()->pluck('id');
            }
            $context = 'overview';
        }

        $tag = $request->get('tag');

        // Build the query and filter based on groups and tags
        $discussions = Discussion::with('group', 'user', 'tags', 'comments')
            ->whereIn('group_id', $groups)
            ->has('user')
            ->withCount('comments')
            ->orderBy('status', 'desc')
            ->orderBy('updated_at', 'desc')
            ->when($tag, function ($query) use ($tag) {
                return $query->withAnyTags($tag);
            });

        // Load unread count if we have a user
        if (Auth::check()) {
            $discussions->with('userReadDiscussion');
        }

        // Handle search
        if ($request->has('search')) {
            $discussions->search($request->get('search'));
        }

        // Paginate the beast
        $discussions = $discussions->paginate(25);

        return $discussions;
    }
}
