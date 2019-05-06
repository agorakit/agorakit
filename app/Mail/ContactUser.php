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
  public function __construct(User $from_user, User $to_user, $body, $reveal_email = false)
  {
    $this->body = $body;
    $this->from_user = $from_user;
    $this->to_user = $to_user;
    $this->reveal_email = $reveal_email; // wether to send as the sender user email or use the generic noreply from
  }

  /**
  * Build the message.
  *
  * @return $this
  */
  public function build()
  {
    if ($this->reveal_email)
    {
      return $this->markdown('emails.contact_direct')
      ->from($this->from_user->email, $this->from_user->name)
      ->subject('['.setting('name').'] '.trans('messages.a_message_for_you'));
    }
    else
    {
      return $this->markdown('emails.contact')
      ->from(config('mail.noreply'), config('mail.from.name'))
      ->subject('['.setting('name').'] '.trans('messages.a_message_for_you'));
    }
  }
}
