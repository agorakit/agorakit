<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Group;
use App\Discussion;
use App\Comment;
use Gate;

class CommentController extends Controller
{
  public function __construct()
  {
    $this->middleware('member', ['only' => ['reply', 'create', 'store', 'edit', 'update', 'destroy']]);
    $this->middleware('verified', ['only' => ['reply', 'create', 'store', 'edit', 'update', 'destroy']]);
  }


  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    //
  }

  public function reply(Request $request, Group $group, Discussion $discussion)
  {
    $comment = new \App\Comment();
    $comment->body = clean($request->input('body'));
    $comment->user()->associate(\Auth::user());

    if ($comment->isInvalid())
    {
      return redirect()->back()
      ->withErrors($comment->getErrors())
      ->withInput();
    }

    $discussion->comments()->save($comment);
    ++$discussion->total_comments;
    $discussion->save();
    $group = $discussion->group;

    return redirect()->action('DiscussionController@show', [$group->id, $discussion->id]);
  }


  /**
  * Display the specified resource.
  *
  * @param int $id
  *
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    //
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param int $id
  *
  * @return \Illuminate\Http\Response
  */
  public function edit(Request $request, Group $group, Discussion $discussion, Comment $comment)
  {
    if (Gate::allows('update', $comment))
    {
      return view('comments.edit')
      ->with('discussion', $discussion)
      ->with('group', $group)
      ->with('comment', $comment)
      ->with('tab', 'discussion');
    }
    else
    {
      abort(403);
    }


  }

  /**
  * Update the specified resource in storage.
  *
  * @param \Illuminate\Http\Request $request
  * @param int                      $id
  *
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $group_id, $discussion_id, $comment_id)
  {
    $comment = \App\Comment::findOrFail($comment_id);
    $discussion = \App\Discussion::findOrFail($discussion_id);

    $this->authorize($comment);

    $comment->body = clean($request->input('body'));

    $comment->saveOrFail();

    $request->session()->flash('message', trans('messages.ressource_updated_successfully'));

    return redirect()->action('DiscussionController@show', [$discussion->group->id, $discussion->id]);

  }



  /**
  * Remove the specified resource from storage.
  *
  * @param int $id
  *
  * @return \Illuminate\Http\Response
  */
  public function destroyConfirm(Request $request, $group_id, $discussion_id, $comment_id)
  {
    $comment = \App\Comment::findOrFail($comment_id);
    $group = \App\Group::findOrFail($group_id);
    $discussion = \App\Discussion::findOrFail($discussion_id);

    if (Gate::allows('delete', $comment))
    {
      return view('comments.delete')
      ->with('discussion', $discussion)
      ->with('group', $group)
      ->with('comment', $comment)
      ->with('tab', 'discussion');
    }
    else
    {
      abort(403);
    }

  }



  /**
  * Remove the specified resource from storage.
  *
  * @param int $id
  *
  * @return \Illuminate\Http\Response
  */
  public function destroy(Request $request, $group_id, $discussion_id, $comment_id)
  {
    $comment = \App\Comment::findOrFail($comment_id);

    if (Gate::allows('delete', $comment))
    {
      $comment->delete();
      $request->session()->flash('message', trans('messages.ressource_deleted_successfully'));
      return redirect()->action('DiscussionController@show', [$group_id, $discussion_id]);
    }
    else
    {
      abort(403);
    }
  }


  /**
  * Show the revision history of the comment
  */
  public function history($group_id, $discussion_id, $comment_id)
  {
    $comment = \App\Comment::findOrFail($comment_id);
    $discussion = $comment->discussion;
    $group = $discussion->group;

    return view('comments.history')
    ->with('group', $group)
    ->with('discussion', $discussion)
    ->with('comment', $comment)
    ->with('tab', 'discussion');
  }



}
