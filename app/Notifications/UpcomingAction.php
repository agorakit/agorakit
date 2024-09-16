<?php

namespace App\Notifications;

use App\Action;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingAction extends Notification
{
    use Queueable;

    public Action $action;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Action $action)
    {
        $this->action = $action;
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
                    ->subject('[' . $this->action->group->name . '] ' . __('You have an upcoming action') .  ': ' . $this->action->name)
                    ->line(__('You have an upcoming action') .  ': ' . $this->action->name)
                    ->line(__('Starts on : ') . $this->action->start->format('d/m/Y H:i'))
                    ->line(__('Ends on : ') . $this->action->stop->format('d/m/Y H:i'))
                    ->line(__('Description : ') . strip_tags($this->action->body))
                    ->action(__('Show'), route('groups.actions.show', [$this->action->group, $this->action]))
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
          'action' => $this->action->toArray(),
        ];
    }
}
