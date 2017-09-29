<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;
use App\Invite;

class InviteUser extends Mailable
{
    use Queueable, SerializesModels;

    public $invite;



    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        return $this->markdown('emails.invite')
        ->from(config('mail.from.address'), config('mail.from.name'))
        ->subject('['.config('app.name').'] '.trans('messages.invitation_to_join').' "'.$this->invite->group->name .'"');
    }
}
