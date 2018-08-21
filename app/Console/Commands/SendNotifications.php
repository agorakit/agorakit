<?php

namespace App\Console\Commands;

use App\Helpers\QueryHelper;
use App\Mailers\AppMailer;
use Illuminate\Console\Command;
use Carbon\Carbon;
use DB;
use App\Group;
use App\User;
use App\Mail\Notification;
use Mail;

class SendNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends all the pending notifications to all users who requested it. This might take time. Call this frequently to avoid trouble';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notifications = $this->getNotificationsToSend();

        if (is_array($notifications)) {
            if (count($notifications) > 0) {
                foreach ($notifications as $notification) {
                    $user = \App\User::find($notification->user_id);
                    $group = \App\Group::find($notification->group_id);

                    if ($user && $group) {
                        $this->line('Checking if there is something to send to user:'.$user->id.' ('.$user->email.')'.' for group:'.$group->id.' ('.$group->name.')');
                        if ($this->sendNotificationEmail($group, $user)) {
                            $this->info('Message sent');
                        } else {
                            $this->error('Nothing sent');
                        }
                    }
                }
            }
        }
    }


    /**
     * Get a list of memberships rows that need to be processed for notification
     * It means people who have opted in for notifcations and who have not been notified for a long enough time
     * (This is configured, per user, per group, in notification_interval in the membership table).
     */
    public function getNotificationsToSend()
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



    public function sendNotificationEmail(Group $group, User $user)
    {
        \App::setLocale(config('app.locale'));

        if ($user->verified == 1) {
            // Establish timestamp for notifications from membership data (when was an email sent for the last time?)
            $membership = \App\Membership::where('user_id', '=', $user->id)
            ->where('group_id', '=', $group->id)
            ->where('membership', '>=', \App\Membership::MEMBER)
            ->first();

            if ($membership) {
                $last_notification = $membership->notified_at;

                // find unread discussions since timestamp
                $discussions = $this->getUnreadDiscussionsSince($user->id, $group->id, $membership->notified_at);

                // find new files since timestamp
                $files = \App\File::where('created_at', '>', $membership->notified_at)
                ->where('group_id', '=', $group->id)
                ->get();

                // find new members since timestamp
                $users = $this->getNewMembersSince($user->id, $group->id, $membership->notified_at);

                // find future actions until next 2 weeks, this is curently hardcoded... TODO use the mail sending interval to determine stop date
                $actions = \App\Action::where('start', '>', Carbon::now())
                ->where('stop', '<', Carbon::now()->addWeek()->addWeek())
                ->where('group_id', '=', $group->id)
                ->orderBy('start')
                ->get();

                // we only trigger mail sending if a new action has been **created** since last notification email.
                // BUT we will send actions for the next two weeks in all cases, IF a mail must be sent
                $actions_count = \App\Action::where('created_at', '>', $membership->notified_at)
                ->where('group_id', '=', $group->id)
                ->count();

                // in all cases update timestamp
                $membership->notified_at = Carbon::now();
                $membership->save();


                // if we have anything, build the message and send
                // removed that : or count($users) > 0
                // because we don't want to be notified just because there is a new member


                if (count($discussions) > 0 or count($files) > 0 or ($actions_count > 0)) {
                    $notification = new Notification;

                    $notification->user = $user;
                    $notification->group = $group;
                    $notification->membership = $membership;
                    $notification->discussions = $discussions;

                    $notification->files = $files;
                    $notification->users = $users;
                    $notification->actions = $actions;
                    $notification->last_notification = $last_notification;


                    Mail::to($user)->send($notification);
                    return true;
                }
            }


            return false;
        }
    }



    /******************************** The following queries are used in the mail notification system : *************************/

    /**
     * Returns a list of unread discussions for the $user_id $user, in the group_id group, since the $since time has passed.
     */
    public function getUnreadDiscussionsSince($user_id, $group_id, $since)
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
     * Returns a list of users that joined the $group_id $since this timestamp.
     * Excludes the $user_id (which might be set to the current user).
     */
    public function getNewMembersSince($user_id, $group_id, $since)
    {
        $users = \App\User::fromQuery('select * from users where id in
        (select user_id from membership where group_id = :group_id and created_at > :since and membership >= :membership and user_id <> :user_id)

        ', ['user_id' => $user_id, 'group_id' => $group_id, 'since' => $since, 'membership' => \App\Membership::MEMBER]);

        return $users;
    }
}
