<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class PwaController extends Controller
{
    public function index()
    {
        $manifest['short_name'] = setting('name');
        $manifest['name'] = setting('name');
        $manifest['description'] = setting('name');


        // 512 px logo
        if (Storage::exists('public/logo/favicon.png')) {
            $icon['src'] = asset('storage/logo/favicon.png');
        } else {
            $icon['src'] = '/logo/agorakit-icon-512.png';
        }

        $icon['type'] =  'image/png';
        $icon['sizes'] = '512x512';

        $manifest['icons'][] = $icon;

        // 192 px logo
        if (Storage::exists('public/logo/favicon.png')) {
            $icon['src'] = asset('storage/logo/favicon.png');
        } else {
            $icon['src'] = '/logo/agorakit-icon-192.png';
        }
        $icon['type'] =  'image/png';
        $icon['sizes'] = '192x192';

        $manifest['icons'][] = $icon;

        $manifest['start_url']= '/';

        $manifest['background_color'] = '#3367D6';
        $manifest['display'] = 'standalone';
        $manifest['scope'] = '/';
        $manifest['theme_color'] = '#3367D6';


        return $manifest;

        /*
        {
        "short_name": "Weather",
        "name": "Weather: Do I need an umbrella?",
        "description": "Weather forecast information",
        "icons": [
        {
        "src": "/images/icons-192.png",
        "type": "image/png",
        "sizes": "192x192"
    },
    {
    "src": "/images/icons-512.png",
    "type": "image/png",
    "sizes": "512x512"
}
],
"start_url": "/?source=pwa",
"background_color": "#3367D6",
"display": "standalone",
"scope": "/",
"theme_color": "#3367D6"
}
*/
}
}
