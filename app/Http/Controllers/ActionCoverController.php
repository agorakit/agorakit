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

    public function small(Action $action)
    {
        $this->authorize('view', $action);
        return $action->getCover('small');
    }

    public function medium(Action $action)
    {
        $this->authorize('view', $action);
        return $action->getCover('medium');
    }

    public function large(Action $action)
    {
        $this->authorize('view', $action);
        return $action->getCover('large');
    }

    public function square(Action $action)
    {
        $this->authorize('view', $action);
        return $action->getCover('square');
    }
}
