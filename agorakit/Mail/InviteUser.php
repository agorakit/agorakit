<?php

namespace Agorakit\Mail;

use URL;
use Agorakit\Membership;
use Agorakit\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteUser extends Mailable
{
    use Queueable, SerializesModels;

    public $membership;
    public $group_user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $group_user, Membership $membership)
    {
        $this->membership = $membership;
        $this->group_user = $group_user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $accept_url = URL::temporarySignedRoute(
            'invite.accept.signed', now()->addDays(30),
            ['membership' => $this->membership->id]
        );

        $deny_url = URL::temporarySignedRoute(
            'invite.deny.signed', now()->addDays(30),
            ['membership' => $this->membership->id]
        );

        return $this->markdown('emails.invite')
        ->from(config('mail.noreply'), config('mail.from.name'))
        ->subject('['.setting('name').'] '.trans('messages.invitation_to_join').' "'.$this->membership->group->name.'"')
        ->with('accept_url', $accept_url)
        ->with('deny_url', $deny_url);
    }
}
