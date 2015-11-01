<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\QueryHelper;
use Carbon\Carbon;
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
    $notifications = QueryHelper::getNotificationsToSend();
    if (count($notifications > 0))
    {

      foreach ($notifications as $notification)
      {
        $this->info('Sending notification to user number ' . $notification->user_id . ' for group number ' . $notification->group_id );
        $this->sendNotificationEmail($notification->group_id, $notification->user_id);
      }
    }

  }


  public function sendNotificationEmail($group_id, $user_id)
  {
    $group = \App\Group::findOrFail($group_id);
    $user = \App\User::findOrFail($user_id);


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

    // find future actions until next mail timestamp
    // TODO must be done on the start and stop date
    $actions = \App\Action::where('start', '>', Carbon::now())
    ->where('group_id', "=", $group->id)->get();

    // if we have anything, build the message and send
    if (count($discussions) > 0 or count($files) > 0 or count($users) > 0 or count($actions) > 0)
    {
      Mail::send('emails.notification', ['user' => $user, 'group' => $group, 'membership' => $membership, 'discussions' => $discussions,
      'files' => $files, 'users' => $users, 'actions' => $actions], function ($message) use($user, $group) {
        $message->from('us@example.com', 'TODO');
        $message->to($user->email);
        $message->subject('Des nouvelles du groupe "' . $group->name . '"');
      });
    }

    // in all cases update timestamp
    $membership->notified_at = Carbon::now();
    $membership->save();


  }

}
