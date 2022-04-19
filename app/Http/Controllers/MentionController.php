<?php

namespace App\Http\Controllers;

use App\Group;

/**
 * Returns a json representation of models, for at.js mention system.
 */
class MentionController extends Controller
{
    public function __construct()
    {
    }

    public function users(Group $group)
    {
        $this->authorize('view-members', $group);

        $users = $group->users()->orderBy('name')->get();
        $simple_users = [];

        foreach ($users as $user) {
            $simple_user['name'] = $user->name;
            $simple_user['id'] = '@'.$user->username;
            $simple_user['userid'] = $user->id;
            $simple_users[] = $simple_user;
        }

        return $simple_users;
    }

    public function discussions(Group $group)
    {
        $this->authorize('view-discussions', $group);

        $discussions = $group->discussions()->orderBy('created_at', 'desc')->get();
        $simple_discussions = [];

        foreach ($discussions as $discussion) {
            $simple_discussion['id'] = $discussion->id;
            $simple_discussion['name'] = $discussion->name;
            $simple_discussion['url'] = route('groups.discussions.show', [$group, $discussion]);
            $simple_discussions[] = $simple_discussion;
        }

        return $simple_discussions;
    }

    public function files(Group $group)
    {
        $this->authorize('view-files', $group);

        $files = $group->files()->orderBy('created_at', 'desc')->get();
        $simple_files = [];

        foreach ($files as $file) {
            $simple_file['id'] = $file->id;
            $simple_file['name'] = $file->name;
            $simple_file['url'] = route('groups.files.show', [$group, $file]);
            $simple_files[] = $simple_file;
        }

        return $simple_files;
    }
}
