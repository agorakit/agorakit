<?php

namespace App\Notifications;

use App\Models\Group;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GroupCreated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Group $group)
    {
        $this->group = $group;
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
                    ->subject('A new group has been created')
                    ->line('A new group has been created : "'.$this->group->name.'"')
                    ->line('Group description : '.strip_tags($this->group->body))
                    ->line('User name : '.$this->group->user->name)
                    ->line('User email : '.$this->group->user->email)
                    ->action('Visit it', route('groups.show', $this->group))
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
        // idea of storing everything from the model taken from here : https://stackoverflow.com/a/44909022
        return [
            'group' => $this->group->toArray(),
        ];
    }
}
