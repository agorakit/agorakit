<?php

namespace App\Http\Controllers;

use App\Tag;
use App\Group;
use Auth;
use Gate;
use Illuminate\Http\Request;

class GroupTagController extends Controller
{
    public function __construct()
    {

    }

    /**
    * Display a listing of tags in the specified group + admin ui for crud
    *
    * @return Response
    */
    public function index(Request $request, Group $group)
    {
        $this->authorize('view-tags', $group);

        $tags = $group->allowedTags();

        return view('tags.index')
        ->with('tags', $tags)
        ->with('group', $group)
        ->with('tab', 'tags');
    }



        /**
        * Display the specified resource.
        *
        * @param int $id
        *
        * @return Response
        */
        public function show(Group $group, Tag $tag)
        {
            $this->authorize('view-tags', $group);


            return view('tags.show')
            ->with('title', $group->name)
            ->with('tag', $tag)
            ->with('group', $group)
            ->with('tab', 'tag');
        }


    /**
    * Show the form for creating a new resource.
    *
    * @return Response
    */
    public function create(Request $request, Group $group)
    {
        $this->authorize('manage-tags', $group);
        $tags = $group->allowedTags();

        return view('tags.create')
        ->with('group', $group)
        ->with('tags', $tags)
        ->with('tab', 'tags');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @return Response
    */
    public function store(Request $request, Group $group)
    {

        $this->authorize('manage-tags', $group);

        $tag = new Tag();
        $tag->name = $request->input('name');
        $tag->color = $request->input('color');


        flash(trans('messages.ressource_created_successfully'));

        return redirect()->route('groups.tags.index', $group);
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

        return view('tags.edit')
        ->with('tag', $tag)
        ->with('group', $group)
        ->with('tab', 'tag');
    }

    /**
    * Update the specified resource in storage.
    *
    * @param int $id
    *
    * @return Response
    */
    public function update(Request $request, Group $group, Tag $tag)
    {
        $this->authorize('manage-tags', $group);

        $tag->name = $request->input('name');
        $tag->color = $request->input('color');

        if ($tag->save()) {
            flash(trans('messages.ressource_updated_successfully'));
            return redirect()->route('groups.tags.index', $group);
        }
        else
        {
            flash(trans('messages.ressource_not_updated_successfully'));
            return redirect()->back();
        }



    }

    public function destroyConfirm(Request $request, Group $group, Tag $tag)
    {
        $this->authorize('manage-tags', $group);

        return view('tags.delete')
        ->with('group', $group)
        ->with('tag', $tag)
        ->with('tab', 'tag');

    }

    /**
    * Remove the specified resource from storage.
    *
    * @param int $id
    *
    * @return \Illuminate\Http\Response
    */
    public function destroy(Request $request, Group $group, Tag $tag)
    {
        $this->authorize('manage-tags', $group);
        //$tag->delete();
        flash(trans('messages.ressource_deleted_successfully'));

        return redirect()->route('groups.tags.index', [$group]);
    }

}
