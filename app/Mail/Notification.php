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
        \App::setLocale(config('app.locale'));

        return $this->markdown('emails.notification')
        ->from(config('mail.noreply'), config('mail.from.name'))
        ->subject('['.setting('name').'] '.trans('messages.news_from_group_email_subject').' "'.$this->group->name.'"');
    }
}
