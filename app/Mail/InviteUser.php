<?php

namespace App\Mail;

use URL;
use App\Membership;
use App\User;
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
        $login_url = URL::temporarySignedRoute(
            'autologin', now()->addDays(15),
            ['username' => $this->membership->user->username, 'redirect' => '/']
        );
        return $this->markdown('emails.invite')
        ->from(config('mail.noreply'), config('mail.from.name'))
        ->subject('['.setting('name').'] '.trans('messages.invitation_to_join').' "'.$this->membership->group->name.'"')
        ->with('login_url', $login_url);
    }
}
