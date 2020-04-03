<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailBounce extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email, $reason = false)
    {
        $this->email = $email;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.bounce')
        ->subject('['.setting('name').'] '. __('Mail not delivered'))
        ->with('reason', $this->reason)
        ->with('subject', $this->email->getSubject())
        ->with('body', $this->email->getBodyText())
        ->from(config('mail.noreply'));
    }
}
