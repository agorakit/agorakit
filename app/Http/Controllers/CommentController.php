<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type, $id)
    {
        if ($type == 'discussion') {
            $discussion = \App\Discussion::findOrFail($id);
            //$group = \App\Group::findOrFail($group_id);
            $group = $discussion->group;

            return view('comments.create')
        ->with('discussion', $discussion)
        ->with('group', $group)
        ->with('tab', 'discussion');
        } else {
            abort(401, 'only discussions can be commented for now');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $type, $id)
    {
        if ($type == 'discussion') {
            $comment = new \App\Comment();
            $comment->body = $request->input('body');

            if (\Auth::check()) {
                $comment->user()->associate(\Auth::user());
            } else {
                abort(401, 'user not logged in TODO');
            }

            $discussion = \App\Discussion::findOrFail($id);
            $discussion->comments()->save($comment);

            $group = $discussion->group;

            return redirect()->action('DiscussionController@show', [$group->id, $discussion->id]);
        } else {
            abort(401, 'only discussions can be commented for now');
        }
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
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
