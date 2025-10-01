<?php

namespace App\Notifications;

use App\Discussion;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Request;

class DiscussionCreated extends Notification
{
    use Queueable;

    public Discussion $discussion;
    public User $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Discussion $discussion, User $user)
    {
        $this->discussion = $discussion;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

        $message =  (new MailMessage())
            ->greeting(' ')
            ->salutation(' ')
            ->subject('[' . $this->discussion->group->name . '] ' . $this->discussion->name)
            ->line(new HtmlString(filter($this->discussion->body)))
            ->line($this->discussion->user->name)
            ->action(trans('messages.reply'), route('groups.discussions.show', [$this->discussion->group, $this->discussion]));
        $message->from(config('mail.noreply'), $this->discussion->user->name);


        // send notification directly from discussion inbox if there is one
        if ($this->discussion->inbox()) {
            $message->replyTo($this->discussion->inbox(), $this->discussion->group->name);
        } else {
            $message->line(trans('messages.dont_reply_to_this_email'));
            $message->from(config('mail.noreply'), config('mail.from.name'));
        }

        $message->withSymfonyMessage(function ($message) {
            $message->getHeaders()->addTextHeader('References', 'discussion-' . $this->discussion->id . '@' . Request::getHost());
            $message->getHeaders()->addIdHeader('agorakit', 'discussion-' . $this->discussion->id . '@' . Request::getHost());
        });

        return $message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'discussion' => $this->discussion->toArray(),
            'user'    => $this->user->toArray(),
        ];
    }
}
