<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Message;

/**
 * Allows admin to list imported message from the inbound mailbox, and check how good or bad they were processed
 */
class MessageController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $messages = Message::all();

        return view('admin.messages.index')->with('messages', $messages);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Message $message)
    {
        return view('admin.messages.show')->with('message', $message);
    }
}
