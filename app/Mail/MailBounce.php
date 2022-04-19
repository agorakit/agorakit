<?php

namespace App\Mail;

use App\Models\Message;
use App\Models\User;
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
        ->subject('['.setting('name').'] '.__('Mail not delivered'))
        ->with('reason', $this->reason)
        ->with('subject', $this->message->subject)
        ->with('body', $this->message->extractText())
        ->from(config('mail.noreply'));
    }
}
