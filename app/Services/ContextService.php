<?php

namespace App\Services;

use App\Group;
use Auth;
use Route;

/**
 * This service is responsible to return the correct context the user is currently in.
 */
class ContextService
{

    /**
     * Returns current context as a string, can be : 
     * - overview
     * - group
     */
    public function get()
    {
        $group = Route::getCurrentRoute()->parameter('group');
        //$group_id = $request->route()->parameter('id');
        // if $request->routeIs('groups.*')
        if ($group && $group->exists) {
            $this->authorize('view-discussions', $group);
            $groups[] = $group->id;
            $context = 'group';
        }
        // If not we need to show some kind of overview
        else {
            if (Auth::check()) {
                // user is logged in, we show according to preferences
                $groups = Auth::user()->getVisibleGroups();
            } else {
                // anonymous users get all public groups
                $groups = Group::public()->pluck('id');
            }
            $context = 'overview';
        }
        return $context;
    }

    /**
     * Set the current context for the current user
     * Contex can be : 
     * - my
     * - all
     * - public
     * - group
     */
    public function set($context) {}

    /**
     * Return true if current context is overview
     */
    public function isOverview()
    {
        return $this->get() == 'overview';
    }

    /**
     * Return true if current context is group
     */
    public function isGroup()
    {
        return $this->get() == 'group';
    }

    /**
     * Return an array of group id's the current user wants to see
     */
    public function getGroupIds()
    {
        $groups = array();

        $group = Route::getCurrentRoute()->parameter('group');
        if ($group && $group->exists) {
            $this->authorize('view-discussions', $group);
            $groups[] = $group->id;
        }
        // If not we need to show some kind of overview
        else {
            if (Auth::check()) {
                // user is logged in, we show according to preferences
                $groups = Auth::user()->getVisibleGroups();
            } else {
                // anonymous users get all public groups
                $groups = Group::public()->pluck('id');
            }
        }
        return $groups;
    }
}
