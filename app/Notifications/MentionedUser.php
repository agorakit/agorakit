<?php

namespace App\Notifications;

use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MentionedUser extends Notification
{
    use Queueable;

    public Comment $comment;
    public User $user;

    /**
    * Create a new notification instance.
    *
    * @return void
    */
    public function __construct(Comment $comment, User $user)
    {
        $this->comment = $comment;
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
        ->subject('[' . $this->comment->discussion->group->name . '] ' .
            trans('You have been mentioned by') . ' ' . $this->user->name)
        ->line(trans('You have been mentioned by') . ' ' . $this->user->name . ' ' .
            trans('in the discussion') . ' ' . $this->comment->discussion->name . ' : ')
        ->line(html_entity_decode(strip_tags(filter($this->comment->body))))
        ->action(trans('Reply'), route(
            'groups.discussions.show',
            [$this->comment->discussion->group, $this->comment->discussion]
        ))
        ->line(trans('Don\'t reply to this email, use the reply button(s) instead'));

        // send notification directly from discussion inbox if there is one
        if ($this->comment->discussion->inbox()) {
            $message->from($this->comment->discussion->inbox(), $this->comment->discussion->group->name);
        } else {
            $message->from(config('mail.noreply'), config('mail.from.name'));
        }

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
            'comment' => $this->comment->toArray(),
            'user'    => $this->user->toArray(),
        ];
    }
}
