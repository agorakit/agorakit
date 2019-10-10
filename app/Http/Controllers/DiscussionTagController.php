<?php

namespace App\Http\Controllers;

use App\Discussion;
use App\File;
use App\Group;
use App\Tag;
use Auth;
use Carbon\Carbon;
use Gate;
use Illuminate\Http\Request;

class DiscussionTagController extends Controller
{

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        $tags = $group->allowedTags();

        return view('discussions.tags')
        ->with('group', $group)
        ->with('discussion', $discussion)
        ->with('all_tags', $tags)
        ->with('discussion_tags', $discussion->tags);
    }



    public function create(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('update', $discussion);
        if ($request->has('tag')) {
            $discussion->tag($request->get('tag'));
        }

        return redirect(route('groups.discussions.tags.edit', [$discussion->group, $discussion]));
    }

    public function destroy(Request $request, Group $group, Discussion $discussion, Tag $tag)
    {
      $this->authorize('update', $discussion);
      $discussion->untag($tag->name);

      return redirect(route('groups.discussions.tags.edit', [$discussion->group, $discussion]));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('update', $discussion);

        if ($request->get('tags')) {
            $discussion->retag($request->get('tags'));
        } else {
            $discussion->detag();
        }

        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->route('groups.discussions.show', [$discussion->group, $discussion]);
    }

}
