<?php

namespace App\Helpers;

use Auth;
use DB;
use Carbon\Carbon;

/**
* Here we have various queries that might be used by different parts of the applications.
* I'm proud of the two firsts one already :-)
* I hate joins as I don't understand it. But subqueries fit my logic.
*
* Potential TODO : return eloquent models anyway instead of simple arrays. But often the arrays contains additional info we need (like read counts)
*
*/
class QueryHelper
{


  /**
  * Returns a list of groups the current user is subscribed to. This one is run on every page
  */
  public static function getUserGroups()
  {

    $groups = DB::select('
    select id, name from groups where groups.id in
    (select group_id from membership where user_id = ? and membership.membership >= 10)
    ', [Auth::user()->id] );

    //dd($groups);

    return $groups;

  }

  /**
  * Returns the number of unread discussions the current user has. Is run on every page !
  */
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


  /******************************** The following queries are used in the mail notification system : *************************/

  /**
  * Returns a list of unread discussions for the $user_id $user, in the group_id group, since the $since time has passed
  */
  public static function  getUnreadDiscussionsSince($user_id, $group_id, $since)
  {

    $discussions = DB::select('select * from
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
  * Returns a list of users that joined the $group_id $since this timestamp. Excludes the $user_id (which might be set to the current user)
  */
  public static function  getNewMembersSince($user_id, $group_id, $since)
  {

    $members = DB::select('select * from users where id in
    (select user_id from membership where group_id = :group_id and created_at > :since and membership >=10 and user_id <> :user_id)

    ', ['user_id' => $user_id, 'group_id' => $group_id, 'since' => $since]);


    return $members;

  }




  /**
  * Get a list of memberships rows that need to be processed for notification
  * It means people who have opted in for notifcations and who have not been notified for a long enough time
  * (This is configured, per user, per group, in notification_interval in the membership table)
  */
  public static function getNotificationsToSend()
  {
    // I use Carbon::now() instead of the now() provided by mysql to avoid different timezone settings in differents servers (php vs mysql config)
    $notifications = DB::select('
    select * from
    (select *, date_add(notified_at, interval notification_interval minute) as notify from membership
    where notification_interval > 0
    and membership >= 10) as memberships
    and membership >= 10) as memberships
    where notify < :now or notify is null
    ', ['now' => Carbon::now()] );


    return $notifications;

  }






}
