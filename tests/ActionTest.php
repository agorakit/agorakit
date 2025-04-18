<?php

use App\Group;

class ActionTest extends Tests\BrowserKitTestCase
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
            ->type('admin@agorakit.org', 'email')
            ->press('Register')
            ->type('123456789', 'password')
            ->type('123456789', 'password_confirmation')
            ->press('Register')
            ->see('Agorakit');

        $this->seeInDatabase('users', ['email' => 'admin@agorakit.org']);
    }

    public function testGroupCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $user->confirmEmail();

        $this->actingAs($user)
            ->visit('groups/create')
            ->see('Create a new group')
            ->type('Action test group', 'name')
            ->type('this is a test group for action test', 'body')
            ->type('France', 'location[country]')
            ->press('Create the group')
            ->see('Action test group');
    }

    public function testActionWithDefiniteEndCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Action test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action with definite end', 'name')
            ->type('this is a test action in the agenda', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('2026-01-03', 'stop_date')
            ->type('9:00', 'stop_time')
            ->press('Create')
            ->seeInDatabase('actions', [
                'name' => 'Test action with definite end',
                'start' => '2026-01-01 12:00:00',
                'stop' => '2026-01-03 09:00:00'
            ])
            ->see(trans('messages.create_action'));
    }

    public function testActionWithWrongStopTimeCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Action test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action with wrong stop time', 'name')
            ->type('this is a test action in the agenda', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('9:00', 'stop_time')
            ->press('Create')
            ->dontSeeInDatabase('actions', [
                'name' => 'Test action with wrong stop time'
            ]);
    }

    public function testActionWithoutStopDateCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Action test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action without stop date', 'name')
            ->type('this is a test action in the agenda', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('17:00', 'stop_time')
            ->press('Create')
            ->seeInDatabase('actions', [
                'name' => 'Test action without stop date',
                'start' => '2026-01-01 12:00:00',
                'stop' => '2026-01-01 17:00:00'
            ])
            ->see(trans('messages.create_action'));
    }

    public function testActionWithoutStopTimeCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Action test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action without stop time', 'name')
            ->type('this is a test action in the agenda', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('2026-01-03', 'stop_date')
            ->press('Create')
            ->seeInDatabase('actions', [
                'name' => 'Test action without stop time',
                'start' => '2026-01-01 12:00:00',
                'stop' => '2026-01-03 12:00:00'
            ])
            ->see(trans('messages.create_action'));
    }

    public function testActionWithUnknownEnd()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Action test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action with unknown end', 'name')
            ->type('this is a test action in the agenda', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->press('Create')
            ->seeInDatabase('actions', [
                'name' => 'Test action with unknown end'
            ])
            ->see(trans('messages.create_action'));
    }

    public function testActionWithLocationName()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Action test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action with location name', 'name')
            ->type('this is a test action in the agenda', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('My Place', 'location[name]')
            ->type('2026-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->press('Create')
            ->seeInDatabase('actions', [
                'name' => 'Test action with location name'
            ])
            ->see(trans('messages.create_action'));
    }

    public function testActionUsingLocationName()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Action test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action us-ing location name', 'name')
            ->type('this is a test action in the agenda', 'body')
	    ->select('My PlaceBruxelles', 'location')
            ->type('2026-02-02', 'start_date')
            ->type('12:00', 'start_time')
            ->press('Create')
            ->seeInDatabase('actions', [
                'name' => 'Test action us-ing location name'
            ])
            ->see(trans('messages.create_action'));
    }
}
