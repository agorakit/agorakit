<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $group;
    public $membership;
    public $discussions;
    public $files;
    public $users;
    public $actions;
    public $last_notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        \App::setLocale(env('APP_DEFAULT_LOCALE', 'en'));

        return $this->markdown('emails.notification')
        ->subject('['.env('APP_NAME').'] '.trans('messages.news_from_group_email_subject').' "'.$this->group->name.'"')
        ->from(env('MAIL_NOREPLY', 'noreply@example.com'), env('APP_NAME', 'Laravel'));

/*
        Mail::send('emails.notification', ['user' => $user, 'group' => $group, 'membership' => $membership, 'discussions' => $discussions,
        'files'                                   => $files, 'users' => $users, 'actions' => $actions, 'last_notification' => $last_notification, ], function ($message) use ($user, $group) {
            $message->from(env('MAIL_NOREPLY', 'noreply@example.com'), env('APP_NAME', 'Laravel'))
            ->to($user->email)
            ->subject('['.env('APP_NAME').'] '.trans('messages.news_from_group_email_subject').' "'.$group->name.'"');
        });
        */


        return $this->view('view.name');
    }
}
