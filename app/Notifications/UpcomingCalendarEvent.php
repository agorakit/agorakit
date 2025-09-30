<?php

namespace App\Notifications;

use App\CalendarEvent;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UpcomingCalendarEvent extends Notification
{
    use Queueable;

    public CalendarEvent $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CalendarEvent $event)
    {
        $this->event = $event;
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
                    ->subject('[' . $this->event->group->name . '] ' . __('You have an upcoming event') .  ': ' . $this->event->name)
                    ->line(__('You have an upcoming event') .  ': ' . $this->event->name)
                    ->line(__('Starts on : ') . $this->event->start->format('d/m/Y H:i'))
                    ->line(__('Ends on : ') . $this->event->stop->format('d/m/Y H:i'))
                    ->line(__('Description : ') . strip_tags($this->event->body))
                    ->action(__('Show'), route('groups.calendarevents.show', [$this->event->group, $this->event]))
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
          'event' => $this->event->toArray(),
        ];
    }
}
