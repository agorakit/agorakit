<?php

namespace App\Http\Controllers;

use App\Event;
use File;
use Image;

/*
Handle group cover image
*/

class EventCoverController extends Controller
{

    public function __construct()
    {
        $this->middleware('cache.headers:private,max-age=300;etag');
    }

    public function show(Event $event, $size = 'medium')
    {
        if ($event->hasCover()) {
            return $event->getCover($size);
        } else {
            abort(404);
        }
    }
}
