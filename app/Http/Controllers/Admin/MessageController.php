<?php

namespace App\Http\Controllers\Admin;

use App\Adapters\ImapMessage;
use App\Adapters\ImapServer;
use App\Http\Controllers\Controller;

/**
 * Allows admin to list imported message from the inbound mailbox, and check how good or bad they were processed
 */
class MessageController extends Controller
{
    /**
    * Display a listing of the resource.
    */
    public function index()
    {
        $messages = ImapServer::getInstance()->getMessages();

        return view('admin.messages.index')->with('messages', $messages);
    }

    /**
    * Display a listing of the resource.
    */
    public function show(ImapMessage $message)
    {

        return view('admin.messages.show')->with('message', $message);
    }
}
