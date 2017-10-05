<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\User;

class ContactUser extends Mailable
{
    use Queueable, SerializesModels;

    public $body;

    public $to_user;
    public $from_user;

    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct(User $from_user, User $to_user, $body)
    {
        $this->body = $body;
        $this->from_user = $from_user;
        $this->to_user = $to_user;
    }

    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->markdown('emails.contact')
        ->subject('['.config('app.name').'] '.trans('messages.a_message_for_you'));
    }
}
