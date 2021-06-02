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
            } else {
                $message->replyTo($this->group->inbox(), $this->group->name);
            }

            /*
            // old behavior was :
            // if only one discussion, send from this discussion instead of the whole group inbox
            if ($this->discussions->count() == 1) {
                $message->replyTo($this->discussions->first()->inbox(), $this->group->name);
            }
            else {
                //send from group because we don't know to which discussion we want the replies to be delivered
                $message->replyTo($this->group->inbox(), $this->group->name);
            }
            */
        } else {
            $message->from(config('mail.noreply'), config('mail.from.name'));
        }

        /*
        // add a correct message id
        if ($this->discussions->count() > 0) {
            $message->replyTo($this->discussions->first()->inbox(), $this->group->name);

            $message->withSwiftMessage(function ($swiftMessage) {
                $swiftMessage->getHeaders()->addTextHeader(
                    'Message-id',
                    'Header Value'
                );
            });

        }*/



        return $message;
    }
}
