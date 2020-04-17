<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use \App\User;
use URL;

class LoginByEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
    * Create a new message instance.
    *
    * @return void
    */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
    * Build the message.
    *
    * @return $this
    */
    public function build()
    {
        $login_url = URL::temporarySignedRoute(
            'autologin', now()->addMinutes(30),
            ['username' => $this->user->username, 'redirect' => '/']
        );
        return $this->markdown('emails.loginbyemail')
        ->subject('['.setting('name').'] '. __('Your login link'))
        ->with('login_url', $login_url);
    }
}
