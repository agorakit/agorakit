<?php

namespace App\Helpers;

use Auth;
use DB;

/**
 * Here we have various queries that might be used by different parts of the applications.
 * I'm proud of the two firsts one already :-)
 * I hate joins as I don't understand it. But subqueries fit my logic.
 *
 * NICETOHAVE : return eloquent models anyway instead of simple arrays. But often the arrays contains additional info we need (like read counts)
 */
class QueryHelper
{
    /**
     * Returns the number of unread discussions the current user has. Is run on every page !
     */
    public static function getUnreadDiscussionsCount()
    {
        if (Auth::check()) {
            $total = DB::select('select sum(total_comments) as count from discussions where discussions.group_id in
            (select id from groups where groups.id in
            (select group_id from membership where user_id = ? and membership.membership >= ?)
            )', [Auth::user()->id, \App\Models\Membership::MEMBER]);

            $read = DB::select('select sum(read_comments) as count from user_read_discussion where user_id = ? and discussion_id in
            (select id from discussions where group_id in
            (select group_id from membership where user_id = ? and membership.membership >= ?)
            )
            ', [Auth::user()->id, Auth::user()->id, \App\Models\Membership::MEMBER]);

            $count = $total[0]->count - $read[0]->count;
            if ($count < 0) {
                $count = 0; // it might happens, it's bad, but not too bad so we fail silently
            }

            return $count;
        }

        return 0;
    }

    /**
     * Returns a list of the 50 latest unread discussions for the current user.
     */
    public static function getUnreadDiscussions()
    {
        if (Auth::check()) {
            $discussions = DB::select('select * from
            (
            select *, (select read_comments from user_read_discussion where discussion_id = discussions.id and user_id = ?) as read_comments
            from discussions where discussions.group_id in
            (select id from groups where groups.id in
            (select group_id from membership where user_id = ? and membership.membership >= ?)
            )
            ) as discussions

            where discussions.total_comments > read_comments or read_comments is null
            order by updated_at desc
            limit 0, 50

            ', [Auth::user()->id, Auth::user()->id, \App\Models\Membership::MEMBER]);

            return $discussions;
        }

        return false;
    }
}
