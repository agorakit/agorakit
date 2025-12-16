<?php

namespace Tests;
use App;


class BaseTest extends BrowserKitTestCase
{
    /* Some utility function*/

     public function admin()
    {
        return App\User::where('email', 'admin@agorakit.local')->firstOrFail();
    }

    public function newbie()
    {
        return App\User::where('email', 'newbie@agorakit.local')->firstOrFail();
    }

    public function getTestGroup()
    {
        return App\Group::where('name', 'Test group')->firstOrFail();
    }

    public function getPrivateGroup()
    {
        return App\Group::where('name', 'Private test group')->firstOrFail();
    }
}