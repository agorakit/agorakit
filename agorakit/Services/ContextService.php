<?php

namespace Agorakit\Services;

use Agorakit\Group;
use Auth;
use Route;

/**
 * This service is responsible to return the correct context the user is currently in.
 * It can be either
 * - a group,
 * - a custom list of groups,
 * - all groups a user is member of,
 * - all public groups,
 * - all groups, even closed ones (admin feature).
 */
class ContextService
{

    public $valid_contexts = ['group', 'joined', 'public', 'all', 'admin'];


    /**
     * Returns current context as a string, can be :
     * - all : admin overview of all discussions for example
     * - joined : all discussions in groups joined by the user
     * - group : a specific group is shown to the user
     * - user : a user profile is shown
     * - admin : server admin area
     */
    public function get()
    {
        if (Route::is('users.*')) {
            return 'user';
        }

        if (Route::is('admin.*')) {
            return 'admin';
        }


        $group = Route::getCurrentRoute()->parameter('group');

        if ($group && $group->exists) {
            return 'group';
        }
        // If not we need to show some kind of overview
        else {
            if (Auth::check()) {
                if (Auth::user()->getPreference('show', 'joined') === 'all' && Auth::user()->isAdmin()) {
                    return 'all';
                }
                if (Auth::user()->getPreference('show', 'joined') === 'public') {
                    return 'public';
                }
                if (Auth::user()->getPreference('show', 'joined') === 'joined') {
                    return 'joined';
                }
            }
        }
        return 'public';
    }

    /**
     * Return true if the passed $context is the current content.
     * $context can be a Group model or 'joined', 'public', 'all', 'group', 'user'
     */
    public function is($context)
    {
        if ($context instanceof Group) {
            $group = Route::getCurrentRoute()->parameter('group');
            return $group && $context->id === $group->id;
        }
        return $this->get() === $context;
    }

    /**
     * Set the current context for the current user
     * Contex can be :
     * - my
     * - public
     * - admin
     */
    public function set($context)
    {
        if (!in_array($context, $this->valid_contexts)) {
            throw new Exception('Invalid context type set');
        }
        session(['context' => $context]);
    }

    /**
     * Return true if current context is some overview
     */
    public function isOverview(): bool
    {
        return (in_array($this->get(), ['joined', 'public', 'all']));
    }

    /**
     * Return true if current context is group
     */
    public function isGroup(): bool
    {
        return ($this->get() === 'group');
    }

    /**
     * Return current group selected in context if there is one (and only one)
     */
    public function getGroup()
    {
        if ($this->is('group')) {
            $group = Route::getCurrentRoute()->parameter('group');
            if ($group && $group->exists) {
                return $group;
            }
        }
        return false;
    }

    /**
     * Return an array of group id's the current user wants (and is allowed) to see
     */
    public function getVisibleGroups()
    {
        $groups = collect();

        $group = Route::getCurrentRoute()->parameter('group');
        if ($group && $group->exists) {
            $groups[] = $group->id;
        }
        // If not we need to show some kind of overview
        else {
            if (Auth::check()) {
                // user is logged in, we show according to preferences
                // a super admin can decide to see all groups
                if ($this->get() === 'all') {
                    if (Auth::user()->isAdmin()) {
                        $groups = Group::pluck('id');
                    } else {
                        // return all groups the user is member of
                        $groups = Auth::user()->groups()->pluck('groups.id');
                    }
                }

                // a normal user can decide to see all his/her groups, including public groups
                if ($this->get() === 'public') {
                    $groups = Group::public()
                        ->pluck('id')
                        ->merge(Auth::user()->groups()->pluck('groups.id'));
                }
                // A user can decide to see only his/her groups :
                if ($this->get() === 'joined') {
                    $groups = Auth::user()->groups()->pluck('groups.id');
                }
            } else {
                // anonymous users get all public groups
                $groups = Group::public()->pluck('id');
            }
        }
        return $groups;
    }
}
