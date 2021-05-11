<?php

namespace App\Http\Controllers;

use App\Comment;
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
    public function store(Request $request, $model, $id, $reaction = null)
    {

        if ($model == 'comment') {
            $model = Comment::findOrFail($id);
            $this->authorize('react', $model);

            if (in_array($reaction, setting()->getArray('reactions'))) {
                Reaction::reactTo($model, $reaction);
            } else {
                Reaction::unReactTo($model);
            }
        }

        return redirect()->back();
    }


    public function destroy(Request $request, $model, $id, $reaction = null)
    {
        if ($model == 'comment') {
            $model = Comment::findOrFail($id);
            if (in_array($reaction, setting()->getArray('reactions'))) {
                $this->authorize('react', $model);
                Reaction::reactTo($model, $reaction);
            }
        }

        return redirect()->back();
    }
}
