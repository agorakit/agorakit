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
        ->subject('['. $this->comment->discussion->group->name . '] ' . trans('messages.you_have_been_mentionned_by'). ' ' . $this->user->name)
        ->line(trans('messages.you_have_been_mentionned_by'). ' ' . $this->user->name . ' ' . trans('messages.in_the_discussion') . ' ' . $this->comment->discussion->name . ' : ')
        ->line($this->comment->body)
        ->action(trans('messages.reply'), action('DiscussionController@show', [$this->comment->discussion->group, $this->comment->discussion]))
        ->line(trans('messages.dont_reply_to_this_email'));
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
