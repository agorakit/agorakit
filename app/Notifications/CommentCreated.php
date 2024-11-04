<?php

namespace App\Notifications;

use App\Comment;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;
use Request;

class CommentCreated extends Notification
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
            ->greeting(' ')
            ->salutation(' ')
            ->subject('[' . $this->comment->discussion->group->name . '] ' . $this->comment->discussion->name)
            ->line(new HtmlString(filter($this->comment->body)))
            ->line($this->comment->user->name)
            ->action(trans('messages.reply'), route('groups.discussions.show', [$this->comment->discussion->group, $this->comment->discussion]));
        $message->from(config('mail.noreply'),  $this->comment->user->name);


        // send notification directly from discussion inbox if there is one
        if ($this->comment->discussion->inbox()) {
            $message->replyTo($this->comment->discussion->inbox(), $this->comment->user->name);
        } else {
            $message->line(trans('messages.dont_reply_to_this_email'));
            $message->from(config('mail.noreply'), config('mail.from.name'));
        }

        $message->withSymfonyMessage(function ($message) {
            $message->getHeaders()->addTextHeader('References',  'discussion-' . $this->comment->discussion->id . '@' . Request::getHost());
            $message->setId('comment-' . $this->comment->id . '@' . Request::getHost());
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
            'comment' => $this->comment->toArray(),
            'user'    => $this->user->toArray(),
        ];
    }
}
