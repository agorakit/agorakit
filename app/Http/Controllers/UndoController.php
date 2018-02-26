<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Undocontroller extends Controller
{
    /**
    * List all actions that can be undoed (undeleted for now)
    */
    public function index()
    {
        // list all instances that have been deleted
        $groups = \App\Group::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $discussions = \App\Discussion::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $comments = \App\Comment::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $files = \App\File::onlyTrashed()->orderBy('deleted_at', 'desc')->get();
        $actions = \App\Action::onlyTrashed()->orderBy('deleted_at', 'desc')->get();

        return view('admin.undo.index')
        ->with('groups', $groups)
        ->with('discussions', $discussions)
        ->with('comments', $comments)
        ->with('files', $files)
        ->with('actions', $actions)
        ;


    }

    public function restore($type, $id)
    {
        if ($type=='group')
        {
            $group = \App\Group::withTrashed()->find($id);
            if ($group->trashed())
            {
                $group->restore();
                return redirect()->route('groups.show', $group);
            }
            else
            {
                abort(404, 'Group is not trashed, cannot restore');
            }
        }

        if ($type=='discussion')
        {
            $discussion = \App\Discussion::withTrashed()->find($id);
            if ($discussion->trashed())
            {
                $discussion->restore();
                return route('discussions.show', $discussion);
            }
            else
            {
                abort(404, 'Discussion is not trashed, cannot restore');
            }
        }

        abort(404, 'Unknown model type');
    }

}
