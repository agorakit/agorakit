<?php

namespace App\Mailers;

use App\Group;
use App\Helpers\QueryHelper;
use App\User;
use Carbon\Carbon;
use Mail;

use App\Mail\Notification;
use App\Mail\UserConfirmation;

class AppMailer
{
    /**
    * Deliver the email confirmation.
    *
    * @param User $user
    *
    * @return void
    */
    public function sendEmailConfirmationTo(User $user)
    {
        Mail::to($user)->send(new UserConfirmation($user));
        return true;
    }

    public function sendNotificationEmail(Group $group, User $user)
    {
        \App::setLocale(config('app.locale'));

        if ($user->verified == 1) {
            // Establish timestamp for notifications from membership data (when was an email sent for the last time?)
            $membership = \App\Membership::where('user_id', '=', $user->id)
            ->where('group_id', '=', $group->id)
            ->firstOrFail();

            $last_notification = $membership->notified_at;

            // find unread discussions since timestamp
            $discussions = QueryHelper::getUnreadDiscussionsSince($user->id, $group->id, $membership->notified_at);

            // find new files since timestamp
            $files = \App\File::where('updated_at', '>', $membership->notified_at)
            ->where('group_id', '=', $group->id)
            ->get();

            // find new members since timestamp
            $users = QueryHelper::getNewMembersSince($user->id, $group->id, $membership->notified_at);

            // find future actions until next 2 weeks, this is curently hardcoded... TODO use the mail sending interval to determine stop date
            $actions = \App\Action::where('start', '>', Carbon::now())
            ->where('stop', '<', Carbon::now()->addWeek()->addWeek())
            ->where('group_id', '=', $group->id)
            ->orderBy('start')
            ->get();

            // we only trigger mail sending if a new action has been **created** since last notfication email.
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


            if (count($discussions) > 0 or count($files) > 0 or ($actions_count > 0 ))
            {
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


            return false;
        }
    }
}
