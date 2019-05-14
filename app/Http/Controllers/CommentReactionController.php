<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Comment;
use \App\Reaction;
use Auth;

/**
 * This controller is curently unused and will at some point allow user to react to comments (+1 -1 ...)
 */
class CommentReactionController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Comment $comment, $context)
    {
        //$comment->reaction($context, Auth::user());
        Reaction::reactTo($comment, $context);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        Reaction::unReactTo($comment);
    }
}
