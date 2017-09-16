<?php

namespace App\Helpers;

use Auth;
use Carbon\Carbon;
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
     * Returns a list of groups the current user is subscribed to. This one is run on every page.
     */
    /*
    public static function getUserGroups()
    {
        $groups = \App\Group::hydrateRaw('
        select * from groups where groups.id in
        (select group_id from membership where user_id = ? and membership.membership = ?) order by name
        ', [Auth::user()->id, \App\Membership::MEMBER] );

        return $groups;
    }
    */ // TODO candidate for destruction, is replaced by the much cleaner Auth::user()->groups()->get()

    /**
     * Returns the number of unread discussions the current user has. Is run on every page !
     */
    public static function getUnreadDiscussionsCount()
    {
        if (Auth::check()) {
            $total = DB::select('select sum(total_comments) as count from discussions where discussions.group_id in
            (select id from groups where groups.id in
            (select group_id from membership where user_id = ? and membership.membership >= ?)
            )', [Auth::user()->id, \App\Membership::MEMBER]);

            $read = DB::select('select sum(read_comments) as count from user_read_discussion where user_id = ? and discussion_id in
            (select id from discussions where group_id in
            (select group_id from membership where user_id = ? and membership.membership >= ?)
            )
            ', [Auth::user()->id, Auth::user()->id, \App\Membership::MEMBER]);

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

            ', [Auth::user()->id, Auth::user()->id, \App\Membership::MEMBER]);

            return $discussions;
        }

        return false;
    }

    /******************************** The following queries are used in the mail notification system : *************************/

    /**
     * Returns a list of unread discussions for the $user_id $user, in the group_id group, since the $since time has passed.
     */
    public static function getUnreadDiscussionsSince($user_id, $group_id, $since)
    {
        $discussions = \App\Discussion::fromQuery('select * from
        (
        select *,
        (select read_comments from user_read_discussion where discussion_id = discussions.id and user_id = :user_id) as read_comments
        from discussions where discussions.group_id = :group_id) as discussions

        where (discussions.total_comments > read_comments or read_comments is null) and discussions.updated_at > :since
        order by updated_at desc
        limit 0, 25

        ', ['user_id' => $user_id, 'group_id' => $group_id, 'since' => $since]);

        return $discussions;
    }

    /**
     * Returns a list of users that joined the $group_id $since this timestamp. Excludes the $user_id (which might be set to the current user).
     */
    public static function getNewMembersSince($user_id, $group_id, $since)
    {
        $users = \App\User::fromQuery('select * from users where id in
        (select user_id from membership where group_id = :group_id and created_at > :since and membership >= :membership and user_id <> :user_id)

        ', ['user_id' => $user_id, 'group_id' => $group_id, 'since' => $since, 'membership' => \App\Membership::MEMBER]);

        return $users;
    }

    /**
     * Get a list of memberships rows that need to be processed for notification
     * It means people who have opted in for notifcations and who have not been notified for a long enough time
     * (This is configured, per user, per group, in notification_interval in the membership table).
     */
    public static function getNotificationsToSend()
    {
        // I use Carbon::now() instead of the now() provided by mysql to avoid different timezone settings in differents servers (php vs mysql config)
        $notifications = DB::select('
        select * from
        (select *, date_add(notified_at, interval notification_interval minute) as notify from membership
        where notification_interval > 0
        and membership >= :membership) as memberships
        where notify < :now or notify is null
        ', ['now' => Carbon::now(), 'membership' => \App\Membership::MEMBER]);

        return $notifications;
    }
}
