<?php

namespace App\Helpers;

use Auth;
use DB;

/**
* Here we have various queries that might be used by different parts of the applications.
* I'm proud of the two firsts one already :-)
* I hate joins as I don't understand it. But subqueries fit my logic.
*/
class QueryHelper
{
  public static function getUnreadDiscussionsCount()
  {
    $total = DB::select('select sum(total_comments) as count from discussions where discussions.group_id in
    (select id from groups where groups.id in
    (select group_id from membership where user_id = ? and membership.membership >= 10)
    )', [Auth::user()->id]);

    $read = DB::select('select sum(read_comments) as count from user_read_discussion where user_id = ? and discussion_id in
    (select id from discussions where group_id in
    (select group_id from membership where user_id = ? and membership.membership >= 10)
    )
    ', [Auth::user()->id, Auth::user()->id]);

    $count = $total[0]->count - $read[0]->count;
    if ($count < 0) {
      $count = 0; // it might happens, it's bad, but not too bad so we fail silently
    }

    return $count;
  }

  /**
   * Returns a list of the 50 latest unread discussions for the current user
   */
  public static function  getUnreadDiscussions()
  {

    $discussions = DB::select('select * from
    (
    select *, (select read_comments from user_read_discussion where discussion_id = discussions.id and user_id = ?) as read_comments
    from discussions where discussions.group_id in
    (select id from groups where groups.id in
    (select group_id from membership where user_id = ? and membership.membership >= 10)
    )
    ) as discussions

    where discussions.total_comments > read_comments or read_comments is null
    order by updated_at desc
    limit 0, 50

    ', [Auth::user()->id, Auth::user()->id]);


    return $discussions;

  }



  public static function  getUnreadDiscussionsSince($user_id, $group_id, $since)
  {

    $discussions = DB::select('select * from
    (
    select *,
    (select read_comments from user_read_discussion where discussion_id = discussions.id and user_id = :user_id) as read_comments
    from discussions where discussions.group_id = :group_id) as discussions

    where discussions.total_comments > read_comments or read_comments is null and discussions.updated_at > :since
    order by updated_at desc
    limit 0, 50

    ', ['user_id' => $user_id, 'group_id' => $group_id, 'since' => $since]);


    return $discussions;

  }


  /**
  * Returns a list of groups the current user is subscribed to.
  */
  public static function  getUserGroups()
  {

    $groups = DB::select('
    select id, name from groups where groups.id in
    (select group_id from membership where user_id = ? and membership.membership >= 10)
    ', [Auth::user()->id] );

    //dd($groups);

    return $groups;

  }


  public static function getNotificationsToSend()
  {
    $notifications = DB::select('
    select * from
    (select *, date_add(notified_on, interval notification_interval minute) as notify from membership
    where notifications > 0
    and membership >= 10) as memberships
    where notify < now() or notify is null
    ');

    return $notifications;

  }






}
