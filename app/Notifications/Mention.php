<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Comment;
use App\User;

class Mention extends Notification
{
    use Queueable;

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
    * @param  mixed  $notifiable
    * @return array
    */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
    * Get the mail representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return \Illuminate\Notifications\Messages\MailMessage
    */
    public function toMail($notifiable)
    {
        return (new MailMessage)
        ->subject('You have been mentionned by ' . $this->user->name)
        ->line('You have been mentionned by ' . $this->user->name . ' in the discussion ' . $this->comment->discussion->name)
        ->line($this->comment->body)
        ->action('Reply', action('DiscussionController@show', [$this->comment->discussion->group, $this->comment->discussion]))
        ->line('Don\'t reply to this email, use the reply button above instead');
    }

    /**
    * Get the array representation of the notification.
    *
    * @param  mixed  $notifiable
    * @return array
    */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
