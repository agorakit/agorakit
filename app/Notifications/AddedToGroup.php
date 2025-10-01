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
                ->subject(trans('messages.you_have_been_added_to_the_group') . ' : "' . $this->group->name . '"')
                ->line(trans('messages.you_have_been_added_to_the_group') . ' : "' . $this->group->name . '"')
                ->action(trans('messages.visit_link'), route('forgotpassword'))
                ->action(trans('messages.visit_link'), route('groups.show', $this->group))
                ->line(trans('messages.thank_you'));
        }
        return (new MailMessage())
            ->subject(trans('messages.you_have_been_added_to_the_group') . ' : "' . $this->group->name . '"')
            ->line(trans('messages.you_have_been_added_to_the_group') . ' : "' . $this->group->name . '"')
            ->action(trans('messages.visit_link'), route('groups.show', $this->group))
            ->line(trans('messages.thank_you'));
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
