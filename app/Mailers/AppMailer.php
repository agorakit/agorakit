<?php
namespace App\Mailers;
use Mail;
use App\User;
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
      $message->to($user->email, $user->name)
      ->subject('[' . env('APP_NAME') . '] ' . trans('messages.confirm_your_email'))
      ->from(env('MAIL_FROM', 'noreply@example.com'));
    });

  }


  public function sendNotificationEmail($group_id, $user_id)
  {
    $group = \App\Group::findOrFail($group_id);
    $user = \App\User::findOrFail($user_id);

    if ($user->verified == 1)
    {

      // Establish timestamp for notifications from membership data (when was an email sent for the last time?)

      $membership = \App\Membership::where('user_id', '=', $user->id)
      ->where('group_id', "=", $group->id)->firstOrFail();

      // find unread discussions since timestamp
      $discussions = QueryHelper::getUnreadDiscussionsSince($user->id, $group->id, $membership->notified_at);


      // find new files since timestamp
      $files = \App\File::where('updated_at', '>', $membership->notified_at)
      ->where('group_id', "=", $group->id)->get();


      // find new members since timestamp
      $users = QueryHelper::getNewMembersSince($user->id, $group->id, $membership->notified_at);

      // find future actions until next 2 weeks, this is curently hardcoded... TODO use the mail sending interval to determine stop date
      $actions = \App\Action::where('start', '>', Carbon::now())->where('stop', '<', Carbon::now()->addWeek()->addWeek() )
      ->where('group_id', "=", $group->id)->get();


      // in all cases update timestamp
      $membership->notified_at = Carbon::now();
      $membership->save();

      // if we have anything, build the message and send
      if (count($discussions) > 0 or count($files) > 0 or count($users) > 0 or count($actions) > 0)
      {
        Mail::send('emails.notification', ['user' => $user, 'group' => $group, 'membership' => $membership, 'discussions' => $discussions,
        'files' => $files, 'users' => $users, 'actions' => $actions], function ($message) use($user, $group) {
          $message->from(env('MAIL_FROM', 'noreply@example.com'));
          $message->to($user->email);
          $message->subject('[' . env('APP_NAME') . '] ' . 'Des nouvelles du groupe "' . $group->name . '"');
        });
        return true;

      }

      return false;


    }


  }


}
