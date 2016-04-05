<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('admin', ['only' => ['settings', 'update']]);
    }


    /**
    * Display a settings edition screen
    *
    * @return \Illuminate\Http\Response
    */
    public function settings()
    {
        return view('admin.settings')
        ->with('homepage_presentation', \App\Setting::get('homepage_presentation'));

    }


    /**
    * Update settings from the edit form
    */
    public function update(Request $request)
    {
        \App\Setting::set('homepage_presentation', $request->input('homepage_presentation'));
        return redirect()->action('DashboardController@index');
    }


}
