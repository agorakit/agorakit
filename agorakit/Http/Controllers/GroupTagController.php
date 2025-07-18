<?php

namespace App\Http\Controllers;

use App\Group;
use App\Tag;
use App\Discussion;
use Illuminate\Http\Request;

class GroupTagController extends Controller
{
    public function __construct()
    {
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit(Request $request, Group $group, Tag $tag)
    {
        $this->authorize('manage-tags', $group);

        // this is a bit hackish :
        // we instantiate a discussion just to get the current allowed tags for discussions, files and actions
        // this is just to access the getAllowedTags() function from the hasControlledTags trait
        $discussion = new Discussion;
        $discussion->group()->associate($group);

        // generate a complete list of tags used in this group to help the admin set correct limitations
        $tags = collect();


        $discussions = $group->discussions()
            ->with('tags')
            ->get();

        $files = $group->files()
            ->with('tags')
            ->get();

        foreach ($discussions as $discussion) {
            foreach ($discussion->tags as $tag) {
                $tags->push($tag);
            }
        }

        foreach ($files as $file) {
            foreach ($file->tags as $tag) {
                $tags->push($tag);
            }
        }

        $tags = $tags->unique('normalized')->sortBy('normalized');


        return view('groups.allowed_tags')
            ->with('group', $group)
            ->with('tags', $tags)
            ->with('newTagsAllowed', true)
            ->with('selectedTags', $discussion->getAllowedTags());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function update(Request $request, Group $group)
    {
        $this->authorize('manage-tags', $group);
        $group->setSetting('allowed_tags', $request->input('tags'));

        flash(trans('messages.ressource_updated_successfully'));
        return redirect()->route('groups.tags.edit', [$group]);
    }
}
