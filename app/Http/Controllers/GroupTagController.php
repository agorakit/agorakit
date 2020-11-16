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
        // this is just to access the hascontrolledtags trait
        $discussion = new Discussion;
        $discussion->group()->associate($group);


        return view('groups.allowed_tags')
        ->with('group', $group)
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
