<?php

namespace Agorakit\Mail;

use Agorakit\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Ddeboer\Imap\Message;

class MailBounce extends Mailable
{
    use Queueable, SerializesModels;

    public $email;
    public $user;

    public $message;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Message $message, $reason = false)
    {
        $this->message = $message;
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
        ->with('subject', $this->message->getSubject())
        ->with('body', $this->message->getBodyHtml())
        ->from(config('mail.noreply'));
    }
}
