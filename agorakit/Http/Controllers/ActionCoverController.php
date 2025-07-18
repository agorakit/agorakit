<?php

namespace App\Http\Controllers;

use App\Action;
use File;
use Image;

/*
Handle group cover image
*/

class ActionCoverController extends Controller
{

    public function __construct()
    {
        $this->middleware('cache.headers:private,max-age=300;etag');
    }

    public function show(Action $action, $size = 'medium')
    {
        if ($action->hasCover()) {
            return $action->getCover($size);
        } else {
            abort(404);
        }
    }
}
