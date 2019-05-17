<?php

namespace App\Http\Controllers;

class Undocontroller extends Controller
{
    public function __construct()
    {
        $this->middleware('admin');
    }

    /**
     * List all actions that can be undoed (undeleted for now).
     */
    public function index()
    {
        // list all instances that have been deleted
        $groups = \App\Group::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
        ->get();

        $discussions = \App\Discussion::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
        ->with('group', 'user')
        ->get();

        $comments = \App\Comment::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
        ->get();

        $files = \App\File::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
        ->with('group', 'user')
        ->get();

        $actions = \App\Action::onlyTrashed()
        ->orderBy('deleted_at', 'desc')
        ->with('group', 'user')
        ->get();

        return view('admin.undo.index')
        ->with('groups', $groups)
        ->with('discussions', $discussions)
        ->with('comments', $comments)
        ->with('files', $files)
        ->with('actions', $actions);
    }

    public function restore($type, $id)
    {
        if ($type == 'group') {
            $group = \App\Group::withTrashed()->find($id);
            if ($group->trashed()) {
                $group->restore();

                return redirect()->route('groups.show', $group);
            } else {
                abort(404, 'Group is not trashed, cannot restore');
            }
        }

        if ($type == 'discussion') {
            $discussion = \App\Discussion::withTrashed()->find($id);
            if ($discussion->trashed()) {
                $group = $discussion->group()->withTrashed()->first();
                // if the group the discussion belongs to is trashed, warn the user
                if ($group->trashed()) {
                    $group->restore();
                }
                $discussion->timestamps = false;
                $discussion->restore();

                return redirect()->route('groups.discussions.show', [$discussion->group, $discussion]);
            } else {
                abort(404, 'Discussion is not trashed, cannot restore');
            }
        }

        if ($type == 'comment') {
            $comment = \App\Comment::withTrashed()->find($id);
            if ($comment->trashed()) {
                $comment->restore();

                return redirect()->route('groups.discussions.show', [$comment->discussion->group, $comment->discussion]);
            } else {
                abort(404, 'comment is not trashed, cannot restore');
            }
        }

        if ($type == 'file') {
            $file = \App\File::withTrashed()->find($id);
            if ($file->trashed()) {
                $group = $file->group()->withTrashed()->first();
                // if the group the discussion belongs to is trashed, warn the user
                if ($group->trashed()) {
                    $group->restore();
                }

                $file->timestamps = false;
                $file->restore();

                return redirect()->route('groups.files.show', [$file->group, $file]);
            } else {
                abort(404, 'file is not trashed, cannot restore');
            }
        }

        if ($type == 'action') {
            $action = \App\Action::withTrashed()->find($id);
            if ($action->trashed()) {
                $group = $action->group()->withTrashed()->first();
                // if the group the discussion belongs to is trashed, warn the user
                if ($group->trashed()) {
                    $group->restore();
                }

                $action->timestamps = false;
                $action->restore();

                return redirect()->route('groups.actions.show', [$action->group, $action]);
            } else {
                abort(404, 'action is not trashed, cannot restore');
            }
        }

        abort(404, 'Unknown model type');
    }
}
