<?php

namespace App\Notifications;

use App\Group;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppliedToGroup extends Notification
{
    use Queueable;

    public Group $group;
    public User $user;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Group $group, User $user)
    {
        $this->group = $group;
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
        return (new MailMessage())
            ->subject(trans('User :user applied to the group :group'))
            ->line(trans('User :user applied to the group :group'))
            ->action(trans('Visit Link'), route('groups.users.index', $this->group))
            ->line(trans('Thank you for using this application'));
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
            'group' => $this->group->toArray(),
            'user'  => $this->user->toArray(),
        ];
    }
}
