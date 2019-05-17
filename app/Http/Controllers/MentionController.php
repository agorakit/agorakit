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
        $this->middleware('member');
    }

    public function users(Group $group)
    {
        $users = $group->users()->get();
        $simple_users = [];

        foreach ($users as $user) {
            $simple_user['id'] = $user->id;
            $simple_user['name'] = $user->name.' ('.$user->username.')';
            $simple_user['username'] = $user->username;
            $simple_user['url'] = route('users.show', $user);
            $simple_users[] = $simple_user;
        }

        return $simple_users;
    }

    public function discussions(Group $group)
    {
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
