<?php
namespace App\Mailers;
use Mail;
use App\User;
use App\Group;
use App\Helpers\QueryHelper;
use Carbon\Carbon;
use Carbon\CarbonInterval;


class AppMailer
{

    /**
    * Deliver the email confirmation.
    *
    * @param  User $user
    * @return void
    */
    public function sendEmailConfirmationTo(User $user)
    {
        Mail::send('emails.confirm', ['user' => $user], function ($message) use ($user) {
            $message->from(env('MAIL_NOREPLY', 'noreply@example.com'), env('APP_NAME', 'Laravel'))
            ->to($user->email, $user->name)
            ->subject('[' . env('APP_NAME') . '] ' . trans('messages.confirm_your_email'));
        });
    }


    public function sendNotificationEmail(Group $group, User $user)
    {
        \App::setLocale(env('APP_DEFAULT_LOCALE', 'en'));

        if ($user->verified == 1)
        {
            // Establish timestamp for notifications from membership data (when was an email sent for the last time?)
            $membership = \App\Membership::where('user_id', '=', $user->id)
            ->where('group_id', "=", $group->id)
            ->firstOrFail();

            $last_notification = $membership->notified_at;

            // find unread discussions since timestamp
            $discussions = QueryHelper::getUnreadDiscussionsSince($user->id, $group->id, $membership->notified_at);


            // find new files since timestamp
            $files = \App\File::where('updated_at', '>', $membership->notified_at)
            ->where('group_id', "=", $group->id)
            ->get();


            // find new members since timestamp
            $users = QueryHelper::getNewMembersSince($user->id, $group->id, $membership->notified_at);

            // find future actions until next 2 weeks, this is curently hardcoded... TODO use the mail sending interval to determine stop date
            $actions = \App\Action::where('start', '>', Carbon::now())
            ->where('stop', '<', Carbon::now()->addWeek()->addWeek())
            ->where('group_id', "=", $group->id)
            ->orderBy('start')
            ->get();

            // we only trigger mail sending if a new action has been **created** since last notfication email.
            // BUT we will send actions for the next two weeks in all cases, IF a mail must be sent
            $actions_count = \App\Action::where('created_at', '>', $membership->notified_at )
            ->where('group_id', "=", $group->id)
            ->count();


            // in all cases update timestamp
            $membership->notified_at = Carbon::now();
            $membership->save();

            // if we have anything, build the message and send
            // removed that : or count($users) > 0
            // because we don't want to be notified just because there is a new member
            if (count($discussions) > 0 or count($files) > 0  or (($actions_count) > 0 && count($actions) > 0))
            {

                Mail::send('emails.notification', ['user' => $user, 'group' => $group, 'membership' => $membership, 'discussions' => $discussions,
                'files' => $files, 'users' => $users, 'actions' => $actions, 'last_notification' => $last_notification], function ($message) use($user, $group) {
                    $message->from(env('MAIL_NOREPLY', 'noreply@example.com'), env('APP_NAME', 'Laravel'))
                    ->to($user->email)
                    ->subject('[' . env('APP_NAME') . '] ' . trans('messages.news_from_group_email_subject') . ' "' . $group->name . '"');
                });
                return true;

            }

            return false;
        }
    }

}
