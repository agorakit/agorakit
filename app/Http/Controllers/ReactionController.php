<?php

namespace App\Http\Controllers;

use App\Comment;
use App\Discussion;
use App\Reaction;
use Auth;
use Illuminate\Http\Request;

/**
 * This controller is curently unused and will at some point allow user to react to comments (+1 -1 ...).
 */
class ReactionController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function react(Request $request, $model, $id, $reaction = null)
    {

        if ($model == 'comment') {
            $model = Comment::findOrFail($id);
            $this->authorize('react', $model);
            Reaction::reactTo($model, $reaction);
        }

        if ($model == 'discussion') {
            $model = Discussion::findOrFail($id);
            $this->authorize('react', $model);
            Reaction::reactTo($model, $reaction);
        }
        
        return redirect()->back();
    }


    /**
     * Remove reaction for the model/id for the current user
     */
    public function unReact(Request $request, $model, $id)
    {
        if ($model == 'comment') {
            $model = Comment::findOrFail($id);
            $this->authorize('react', $model);
            Reaction::unReactTo($model);
        }

        if ($model == 'discussion') {
            $model = Discussion::findOrFail($id);
            $this->authorize('react', $model);
            Reaction::unReactTo($model);
        }

        return redirect()->back();
    }
}
