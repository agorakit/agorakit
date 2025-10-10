<?php

namespace Tests;

use App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class CalendarEventTest extends BrowserKitTestCase
{
    /******************* Why is it done this way ? ***************/

    /*
    I want my tests runs on a clean DB, and each test in the right order, like I would do by hand.
    The first tests migrates the testing DB
    Sounds simplier like this for me, I don't want the database being remigrated after each test.
    Only after the whole suite has been run.

    You need a agorakit_testing DB available for those tests to run.



    */

    /* tests starts here : let's setup the DB
    */
    public function testSetupItAll()
    {
        Artisan::call('migrate:fresh');

        $this->visit('/')
            ->see('Agorakit');
    }

    /**
     * Register our first user.
     */
    public function testUserRegistration()
    {
        Mail::fake();

        $this->visit('/register')
            ->type('Admin', 'name')
            ->type('admin@agorakit.local', 'email')
            ->press('Register')
            ->type('123456789', 'password')
            ->type('123456789', 'password_confirmation')
            ->press('Register')
            ->see('Agorakit');

        $this->seeInDatabase('users', ['email' => 'admin@agorakit.local']);
    }

    public function testGroupCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $user->confirmEmail();

        $this->actingAs($user)
            ->visit('groups/create')
            ->see('Create a new group')
            ->type('Event test group', 'name')
            ->type('this is a test group for event test', 'body')
            ->type('France', 'location[country]')
            ->press('Create the group')
            ->see('Event test group');
    }

    public function testEventWithDefiniteEndCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Event test group')->first();

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
            ->see(trans('messages.create_event'));
    }

    public function testEventWithWrongStopTimeCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Event test group')->first();

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
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Event test group')->first();

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
            ->see(trans('messages.create_event'));
    }

    public function testEventWithoutStopTimeCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Event test group')->first();

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
            ->see(trans('messages.create_event'));
    }

    public function testEventWithUnknownEnd()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Event test group')->first();

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
            ->see(trans('messages.create_event'));
    }

    public function testEventWithLocationName()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Event test group')->first();

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
            ->see(trans('messages.create_event'));
    }

    public function testEventUsingLocationName()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Event test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event us-ing location name', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->select('My PlaceBruxelles', 'listed_location')
            ->type('2026-02-02', 'start_date')
            ->type('12:00', 'start_time')
            ->press('Create')
            ->seeInDatabase('calendar_events', [
                'name' => 'Test event us-ing location name'
            ])
            ->see(trans('messages.create_event'));
    }
}
