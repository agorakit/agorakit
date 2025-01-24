<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Discussion;
use App\File;
use App\Group;
use Auth;
use Illuminate\Http\Request;

/**
 * Comments CRUD controller.
 */
class CommentController extends Controller
{
    public function __construct()
    {
    }

    public function store(Request $request, Group $group, Discussion $discussion)
    {
        $this->authorize('create-comment', $group);
        $comment = new \App\Comment();
        $comment->body = $request->input('body');
        $comment->user()->associate(\Auth::user());

        if ($comment->isInvalid()) {
            return redirect()->back()
                ->withErrors($comment->getErrors())
                ->withInput();
        }

        // handle attached file to comment
        if ($request->hasFile('file')) {

            // create a file instance
            $file = new File();
            $file->forceSave(); // we bypass autovalidation, since we don't have a complete model yet, but we *need* an id

            // add group, user
            $file->group()->associate($group);
            $file->user()->associate(Auth::user());

            // store the file itself on disk
            $file->addToStorage($uploaded_file);

            // add an f:xx to the comment so it is shown on display
            $comment->body = $comment->body . '<p>f:' . $file->id . '</p>';
        }

        $discussion->comments()->save($comment);
        $discussion->total_comments++;
        $discussion->save();

        // update activity timestamp on parent items
        $group->touch();
        $discussion->touch();
        Auth::user()->touch();

        event(new \App\Events\ContentCreated($comment));

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
        $this->authorize('update', $comment);

        return view('comments.edit')
            ->with('discussion', $discussion)
            ->with('group', $group)
            ->with('comment', $comment)
            ->with('tab', 'discussion');
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
        $this->authorize('update', $comment);
        $comment->body = $request->input('body');

        // handle attached file to comment
        if ($request->hasFile('file')) {
            // create a file instance
            $file = new File();
            $file->forceSave(); // we bypass autovalidation, since we don't have a complete model yet, but we *need* an id

            // add group, user
            $file->group()->associate($group);
            $file->user()->associate(Auth::user());

            // store the file itself on disk
            $file->addToStorage($uploaded_file);

            // add an f:xx to the comment so it is shown on display
            $comment->body = $comment->body . '<p>f:' . $file->id . '</p>';
            }
        

        if ($comment->isInvalid()) {
            return redirect()->back()
                ->withErrors($comment->getErrors())
                ->withInput();
        }
        $comment->save();
        flash(trans('messages.ressource_updated_successfully'));

        return redirect()->route('groups.discussions.show', [$discussion->group, $discussion]);
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
        $this->authorize('delete', $comment);

        return view('comments.delete')
            ->with('discussion', $discussion)
            ->with('group', $group)
            ->with('comment', $comment)
            ->with('tab', 'discussion');
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
        $this->authorize('update', $comment);
        $comment->delete();
        flash(trans('messages.ressource_deleted_successfully'));

        return redirect()->route('groups.discussions.show', [$group, $discussion]);
    }

    /**
     * Show the revision history of the comment.
     */
    public function history(Request $request, Group $group, Discussion $discussion, Comment $comment)
    {
        $this->authorize('history', $comment);

        return view('comments.history')
            ->with('group', $group)
            ->with('discussion', $discussion)
            ->with('comment', $comment)
            ->with('tab', 'discussion');
    }


    /**
     * This function is called by the comment polling code
     * It should be fast and simple and return only $comments after $comment
     */
    public function live(Request $request, Group $group, Discussion $discussion, Comment $comment)
    {
        $this->authorize('view', $discussion);
        $comments = $discussion->comments()->where('id', '>', $comment->id)->get();

        return view('comments.live')
            ->with('read_comments', 0)
            ->with('group', $group)
            ->with('discussion', $discussion)
            ->with('comments', $comments);
    }
}
