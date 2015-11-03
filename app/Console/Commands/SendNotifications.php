<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\QueryHelper;
use Carbon\Carbon;
use Mail;
use App\Mailers\AppMailer;

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
        $mailer = new AppMailer();
        $mailer->sendNotificationEmail($notification->group_id, $notification->user_id);
      }
    }

  }




}
