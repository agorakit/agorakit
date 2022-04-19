<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NavigationController extends Controller
{
    public function show(Request $request)
    {
        return view('navigation.main');
    }
}
