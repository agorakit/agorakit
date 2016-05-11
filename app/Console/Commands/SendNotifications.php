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
                $user = \App\User::find($notification->user_id);
                $group = \App\Group::find($notification->group_id);

                if ($user && $group)
                {
                    $this->info('Checking if there is something to send to user:' . $user->id . ' (' . $user->email .  ')'  .  ' for group:' . $group->id . ' (' . $group->name .  ')' );
                    $mailer = new AppMailer();
                    if ($mailer->sendNotificationEmail($group, $user))
                    {
                        $this->info('Message sent');
                    }
                    else
                    {
                        $this->info('Nothing sent');
                    }
                }
            }
        }
    }




}
