<?php

namespace App\Services;

use App\CalendarEvent;
use Illuminate\Database\Eloquent\Collection;

class CalendarEventService
{

    /**
     * Converts a CalendarEvent to a json representation that can be understood by fullcalendar JS
     */
    public static function calendarEventToFullCalendarJson(CalendarEvent $event): array
    {
        $json_event['id'] = $event->id;
        $json_event['title'] = $event->name . ' (' . $event->group->name . ')';
        $json_event['description'] = strip_tags(summary($event->body)) . ' <br/> ' . $event->locationDisplay();
        $json_event['body'] = strip_tags(summary($event->body));
        $json_event['summary'] = strip_tags(summary($event->body));
        $json_event['tooltip'] =  '<strong>' . strip_tags(summary($event->name)) . '</strong>';
        $json_event['tooltip'] .= '<div>' . strip_tags(summary($event->body)) . '</div>';
        $json_event['location'] = $event->locationDisplay();
        $json_event['start'] = $event->start->toIso8601String();
        $json_event['end'] = $event->stop->toIso8601String();
        $json_event['url'] = route('groups.calendarevents.show', [$event->group, $event]);
        $json_event['group_url'] = route('groups.calendarevents.index', [$event->group]);
        $json_event['group_name'] = $event->group->name;
        $json_event['color'] = $event->group->color();

        if ($event->attending->count() > 0) {
            $json_event['tooltip'] .= '<strong class="mt-2">' . trans('messages.user_attending') . '</strong>';
            $json_event['tooltip'] .= '<div>' . implode(', ', $event->attending->pluck('username')->toArray()) . '</div>';
        }

        return $json_event;
    }

    /**
     * Converts a CalendarEvent collection to a json representation that can be understood by fullcalendar JS
     */
    public static function calendarEventsToFullCallendarJson(Collection $events): array
    {
        $json_events = [];
        foreach ($events as $event) {
            $json_events[] = self::calendarEventToFullCalendarJson($event);
        }
        return $json_events;
    }
}
