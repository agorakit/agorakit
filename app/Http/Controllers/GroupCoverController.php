<?php

namespace App\Http\Controllers;

use App\Group;
use File;
use Image;

/*
Handle group cover image
*/

class GroupCoverController extends Controller
{
    public function __construct()
    {
        $this->middleware('cache.headers:private,max-age=300;etag');
    }

    public function show(Group $group, $size = 'medium')
    {
        if ($group->hasCover()) {
            return $group->getCover($size);
        } else {
            abort(404);
        }
    }
}
