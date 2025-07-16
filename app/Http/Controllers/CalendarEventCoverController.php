<?php

namespace App\Http\Controllers;

use App\CalendarEvent;
use File;
use Image;

/*
Handle group cover image
*/

class CalendarEventCoverController extends Controller
{

    public function __construct()
    {
        $this->middleware('cache.headers:private,max-age=300;etag');
    }

    public function show(CalendarEvent $event, $size = 'medium')
    {
        if ($event->hasCover()) {
            return $event->getCover($size);
        } else {
            abort(404);
        }
    }
}
