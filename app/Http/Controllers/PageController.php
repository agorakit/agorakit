<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function help()
    {
        return view('pages.help');
    }
}
