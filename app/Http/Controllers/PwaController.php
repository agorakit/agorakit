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
        $icon['src'] = url('icon/512');
        $icon['type'] =  'image/png';
        $icon['sizes'] = '512x512';

        $manifest['icons'][] = $icon;

        // 192 px logo
        $icon['src'] = url('icon/192');
        $icon['type'] =  'image/png';
        $icon['sizes'] = '192x192';

        $manifest['icons'][] = $icon;


        $manifest['start_url']= '/';

        $manifest['background_color'] = '#3367D6';
        $manifest['display'] = 'standalone';
        $manifest['scope'] = '/';
        $manifest['theme_color'] = '#3367D6';


        return $manifest;

    }
}
