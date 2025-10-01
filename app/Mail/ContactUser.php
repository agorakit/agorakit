<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactUser extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $body;

    public $to_user;
    public $from_user;
    public $reveal_email;

    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct(User $from_user, User $to_user, $body, $reveal_email = false)
    {
        $this->body = $body;
        $this->from_user = $from_user;
        $this->to_user = $to_user;
        $this->reveal_email = $reveal_email; // wether to send as the sender user email or use the generic noreply from
        if ($reveal_email) {
            $this->replyTo($from_user);
        }
    }

    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
            return $this->markdown('emails.contact')
            ->from(config('mail.noreply'), config('mail.from.name'))
            ->subject('[' . setting('name') . '] ' . trans('messages.a_message_for_you'));
    }
}
