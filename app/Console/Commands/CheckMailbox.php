<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpImap\Mailbox;
use App\User;
use App\Group;
use App\Discussion;

class CheckMailbox extends Command
{
    /**
    * The name and signature of the console command.
    *
    * @var string
    */
    protected $signature = 'agorakit:checkmailbox';

    /**
    * The console command description.
    *
    * @var string
    */
    protected $description = 'Check the configured email pop server to allow post by email functionality';

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
        // open mailbox
        $mailbox = new Mailbox('{' . setting('mail_server') . ':110/pop3}INBOX',  setting('mail_login'), setting('mail_password'), __DIR__);

        $mail_ids = $mailbox->searchMailbox();

        foreach ($mail_ids as $mail_id)
        {
            $delete_mail = false;
            $mail = $mailbox->getMail($mail_id);

            //print_r($mail);

            $this->info('Got a message from ' . $mail->fromAddress);

            // check that is is sent from an existing user
            $user = User::where('email', $mail->fromAddress)->first();
            if ($user)
            {
                $this->info('This user exists, full name is ' . $user->name . ' / id is : ' . ($user->id));

                // check that mail to: is an existing group
                foreach ($mail->headers->to as $to)
                {
                    // check that is is sent to an existing group
                    $group = Group::where('slug',$to->mailbox)->first();
                    if ($group)
                    {
                        $this->info('Group ' . $group->name . ' exists');

                        // check that the user is a member of the group
                        if ($user->isMemberOf($group))
                        {
                            $this->info($user->name . ' is a member of this group');

                            $discussion = new Discussion;

                            $discussion->name = $mail->subject;
                            $discussion->body = $mail->textHtml;

                            $discussion->total_comments = 1; // the discussion itself is already a comment
                            $discussion->user()->associate($user);

                            if ($group->discussions()->save($discussion))
                            {
                                // update activity timestamp on parent items
                                $group->touch();
                                $user->touch();
                                $delete_mail = true;
                            }
                            else
                            {
                                $this->error('Could not create discussion');

                            }



                        }
                        else
                        {
                            $this->error($user->name . ' is not a member of ' . $group->name);
                            $delete_mail = true;
                        }

                    }
                    else
                    {
                        $this->error('No group named ' . $to->mailbox);
                        $delete_mail = true;
                    }
                }

            }
            else
            {
                $this->error('No user found with ' . $mail->fromAddress);
                $delete_mail = true;
            }


            if ($delete_mail)
            {
                $mailbox->deleteMail($mail_id);
                $this->info('Email has been deleted from mail server');
                $delete_mail = false;
            }

            $this->info('-----------------------------------');
        }


    }
}
