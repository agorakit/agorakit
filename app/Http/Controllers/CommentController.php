<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Discussion;
use App\Group;
use Gate;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('member', ['only' => ['reply', 'create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('verified', ['only' => ['reply', 'create', 'store', 'edit', 'update', 'destroy']]);
        $this->middleware('public', ['only' => ['reply', 'create', 'store', 'edit', 'update', 'destroy']]);
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
        $comment->body = $request->input('body');
        $comment->user()->associate(\Auth::user());

        if ($comment->isInvalid()) {
            return redirect()->back()
            ->withErrors($comment->getErrors())
            ->withInput();
        }

        $discussion->comments()->save($comment);
        ++$discussion->total_comments;
        $discussion->save();


        return redirect()->route('groups.discussions.show', [$discussion->group, $discussion]);
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
        if (Gate::allows('update', $comment)) {
            return view('comments.edit')
            ->with('discussion', $discussion)
            ->with('group', $group)
            ->with('comment', $comment)
            ->with('tab', 'discussion');
        } else {
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
    public function update(Request $request, Group $group, Discussion $discussion, Comment $comment)
    {
        if (Gate::allows('update', $comment)) {
            $comment->body = $request->input('body');

            if ($comment->isInvalid()) {
                return redirect()->back()
                ->withErrors($comment->getErrors())
                ->withInput();
            }
            $comment->save();
            flash(trans('messages.ressource_updated_successfully'))->success();

            return redirect()->route('groups.discussions.show', [$discussion->group, $discussion]);
        } else {
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
    public function destroyConfirm(Request $request, Group $group, Discussion $discussion, Comment $comment)
    {
        if (Gate::allows('delete', $comment)) {
            return view('comments.delete')
            ->with('discussion', $discussion)
            ->with('group', $group)
            ->with('comment', $comment)
            ->with('tab', 'discussion');
        } else {
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
    public function destroy(Request $request, Group $group, Discussion $discussion, Comment $comment)
    {
        if (Gate::allows('delete', $comment)) {
            $comment->delete();
            flash(trans('messages.ressource_deleted_successfully'))->success();

            return redirect()->route('groups.discussions.show', [$group, $discussion]);
        } else {
            abort(403);
        }
    }

    /**
    * Show the revision history of the comment.
    */
    public function history(Request $request, Group $group, Discussion $discussion, Comment $comment)
    {
        return view('comments.history')
        ->with('group', $group)
        ->with('discussion', $discussion)
        ->with('comment', $comment)
        ->with('tab', 'discussion');
    }
}
