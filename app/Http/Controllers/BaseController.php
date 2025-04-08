<?php

namespace App\Http\Controllers;


/**
 * The base controller provide shared methods for other controllers.
 * For instance it can detect context (user is in a group, is in overview, in admin settings, etc...)
 */
class BaseController extends Controller
{
    /**
     * Returns the current context, which can be
     * - group
     * - overview
     */
    public function getcontext()
    {

    }



    public function getGroups()
    {
        
    }
    
}
