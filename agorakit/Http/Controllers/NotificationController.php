<?php

namespace Agorakit\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return view('notifications.index')
            ->with('notifications', $request->user()->unreadNotifications()->paginate(50));
    }
}
