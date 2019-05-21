<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Discussion;
use App\File;
use App\User;
use App\Action;
use Auth;
use App\Tag;

/**
* This controller is used for quick tag editing on various models (discussions & files curently).
*/
class TagController extends Controller
{


  public function index(Request $request)
  {
    /*
    $tagService = app(\Cviebrock\EloquentTaggable\Services\TagService::class);
    $tags = $tagService->getAllTags();
    */

    $tags = collect();

    foreach ($request->user()->groups as $group)
    {
      $tags =  $tags->merge($group->tagsUsed());
    }

    $tags = $tags->sortKeys();


    return view('tags.index')
    ->with('tags', $tags);

    // TODO query tags from user's groups
    // TODO auto add tags to group when something is tagged


    /*

    $groups = Auth::user()->groups()->pluck('groups.id');


    // Magic query to get all the users who have one of the groups defined above in their membership table
    $discussion = Discussion::whereHas('group', function($q) use ($groups) {
    $q->whereIn('group_id', $groups);
  })
  ->isTagged();

  dd($discussion);




  $groups = Auth::user()->groups()->pluck('groups.id');


  // Magic query to get all the users who have one of the groups defined above in their membership table
  $users = User::whereHas('groups', function($q) use ($groups) {
  $q->whereIn('group_id', $groups);
})
->where('verified', 1)
->orderBy('created_at', 'desc')
->paginate(20);
*/
}

public function show(Request $request, Tag $tag)
{


  $groups = Auth::user()->groups()->pluck('groups.id');


  $discussions = Discussion::whereHas('group', function($q) use ($groups) {
    $q->whereIn('group_id', $groups);
  })
  ->whereHas('tags', function($q) use ($tag) {
    $q->where('normalized', $tag->normalized);
  })
  ->get();

  $files = File::whereHas('group', function($q) use ($groups) {
    $q->whereIn('group_id', $groups);
  })
  ->whereHas('tags', function($q) use ($tag) {
    $q->where('normalized', $tag->normalized);
  })
  ->get();

  $actions = Action::whereHas('group', function($q) use ($groups) {
    $q->whereIn('group_id', $groups);
  })
  ->whereHas('tags', function($q) use ($tag) {
    $q->where('normalized', $tag->normalized);
  })
  ->get();

  $users = User::whereHas('groups', function($q) use ($groups) {
    $q->whereIn('group_id', $groups);
  })
  ->whereHas('tags', function($q) use ($tag) {
    $q->where('normalized', $tag->normalized);
  })
  ->get();




  return view('tags.show')
  ->with('discussions', $discussions)
  ->with('files', $files)
  ->with('users', $users)
  ->with('actions', $actions)
  ->with('tag', $tag);

}

/**
* Show the form for editing the specified resource.
*
* @param int $id
*
* @return \Illuminate\Http\Response
*/
public function edit(Request $request, $group, $type, $id)
{
  if ($type == 'discussions') {
    $model = \App\Discussion::findOrFail($id);

    return view('tags.edit')
    ->with('name', $model->name)
    ->with('group', $model->group)
    ->with('model', $model)
    ->with('type', $type)
    ->with('id', $id)
    ->with('model_tags', $model->tags)
    ->with('all_tags', $model->group->tagsUsed());
  }

  if ($type == 'files') {
    $model = \App\File::findOrFail($id);

    return view('tags.edit')
    ->with('name', $model->name)
    ->with('group', $model->group)
    ->with('model', $model)
    ->with('type', $type)
    ->with('id', $id)
    ->with('model_tags', $model->tags)
    ->with('all_tags', $model->group->tagsUsed());
  }

  abort(404, 'Unknown type');
}

/**
* Update the specified resource in storage.
*
* @param \Illuminate\Http\Request $request
* @param int                      $id
*
* @return \Illuminate\Http\Response
*/
public function update(Request $request, $group, $type, $id)
{
  if ($type == 'discussions') {
    $model = \App\Discussion::findOrFail($id);
    $model->tag($request->get('tags'));
    flash(trans('messages.ressource_created_successfully'));

    return redirect()->route('groups.discussions.show', [$model->group, $model]);
  }

  if ($type == 'files') {
    $model = \App\File::findOrFail($id);
    $model->tag($request->get('tags'));
    flash(trans('messages.ressource_created_successfully'));

    return redirect()->route('groups.files.show', [$model->group, $model]);
  }

  abort(404, 'Unknown type');
}
}
