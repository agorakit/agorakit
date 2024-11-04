<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Notification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $group;
    public $membership;
    public $discussions;
    public $files;
    public $users;
    public $actions;
    public $last_notification;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->user->getPreference('locale')) {
            \App::setLocale($this->user->getPreference('locale'));
        } else {
            \App::setLocale(config('app.locale'));
        }

        $message = $this->markdown('emails.notification')
            ->subject('[' . setting('name') . '] ' . trans('messages.news_from_group_email_subject') . ' "' . $this->group->name . '"');

        if ($this->group->inbox()) {
            $message->from($this->group->inbox(), $this->group->name);

            // experimental : let's send from the first discussion in most cases
            if ($this->discussions->count() > 0) {
                $message->replyTo($this->discussions->first()->inbox(), $this->group->name);
                $message->withSymfonyMessage(function ($message) {
                    $message->getHeaders()->addTextHeader(
                        'References',
                        $this->discussions->first()->inbox()
                    );
                    $message->getHeaders()->addTextHeader(
                        'In-Reply-To',
                        $this->discussions->first()->inbox()
                    );
                });
            } else {
                $message->replyTo($this->group->inbox(), $this->group->name);
            }
        } else {
            $message->from(config('mail.noreply'), config('mail.from.name'));
        }





        return $message;
    }
}
