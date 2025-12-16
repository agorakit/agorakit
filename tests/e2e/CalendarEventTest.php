<?php

namespace Tests;

use App;
use Tests\BaseTest;

class CalendarEventTest extends BaseTest
{
    public function testEventWithDefiniteEndCreation()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event with definite end', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('2026-01-03', 'stop_date')
            ->type('9:00', 'stop_time')
            ->press('Create')
            ->seeInDatabase('calendar_events', [
                'name' => 'Test event with definite end',
                'start' => '2026-01-01 12:00:00',
                'stop' => '2026-01-03 09:00:00'
            ])
            ->see(trans('messages.ressource_created_successfully'));
    }

    public function testEventWithWrongStopTimeCreation()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event with wrong stop time', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('9:00', 'stop_time')
            ->press('Create')
            ->dontSeeInDatabase('calendar_events', [
                'name' => 'Test event with wrong stop time'
            ]);
    }

    public function testEventWithoutStopDateCreation()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event without stop date', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('17:00', 'stop_time')
            ->press('Create')
            ->seeInDatabase('calendar_events', [
                'name' => 'Test event without stop date',
                'start' => '2026-01-01 12:00:00',
                'stop' => '2026-01-01 17:00:00'
            ])
            ->see(trans('messages.ressource_created_successfully'));
    }

    public function testEventWithoutStopTimeCreation()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event without stop time', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('2026-01-03', 'stop_date')
            ->press('Create')
            ->seeInDatabase('calendar_events', [
                'name' => 'Test event without stop time',
                'start' => '2026-01-01 12:00:00',
                'stop' => '2026-01-03 12:00:00'
            ])
            ->see(trans('messages.ressource_created_successfully'));
    }

    public function testEventWithUnknownEnd()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event with unknown end', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->press('Create')
            ->seeInDatabase('calendar_events', [
                'name' => 'Test event with unknown end'
            ])
            ->see(trans('messages.ressource_created_successfully'));
    }

    public function testEventWithLocationName()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event with location name', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('My Place', 'location[name]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->press('Create')
            ->seeInDatabase('calendar_events', [
                'name' => 'Test event with location name'
            ])
            ->see(trans('messages.ressource_created_successfully'));
    }

    public function testEventUsingLocationName()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event using location name', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->select('My PlaceBruxelles', 'listed_location')
            ->type('2026-02-02', 'start_date')
            ->type('12:00', 'start_time')
            ->press('Create')
            ->seeInDatabase('calendar_events', [
                'name' => 'Test event using location name'
            ])
            ->see(trans('messages.ressource_created_successfully'));
    }
}
