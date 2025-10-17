<?php

namespace App\Mail;

use App\Adapters\ImapMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MailBounce extends Mailable
{
    use Queueable;
    use SerializesModels;

    public $email;
    public $user;

    public $message;
    public $reason;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ImapMessage $message, $reason = false)
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
        ->subject('[' . setting('name') . '] ' . __('Mail not delivered'))
        ->with('reason', $this->reason)
        ->with('subject', $this->message->getSubject())
        ->with('body', $this->message->getBodyHtml())
        ->from(config('mail.noreply'));
    }
}
