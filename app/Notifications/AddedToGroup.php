<?php

namespace App\Notifications;

use App\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddedToGroup extends Notification
{
    use Queueable;

    public Group $group;

    protected bool $resetlink;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Group $group, $resetlink = false)
    {
        $this->group = $group;
        $this->resetlink = $resetlink;
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
        if ($this->resetlink) {
            return (new MailMessage())
                ->subject(trans('You have been added to the group') . ' : "' . $this->group->name . '"')
                ->line(trans('You have been added to the group') . ' : "' . $this->group->name . '"')
                ->action(trans('Visit Link'), route('forgotpassword'))
                ->action(trans('Visit Link'), route('groups.show', $this->group))
                ->line(trans('Thank you for using this application'));
        }
        return (new MailMessage())
            ->subject(trans('You have been added to the group') . ' : "' . $this->group->name . '"')
            ->line(trans('You have been added to the group') . ' : "' . $this->group->name . '"')
            ->action(trans('Visit Link'), route('groups.show', $this->group))
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
        ];
    }
}
