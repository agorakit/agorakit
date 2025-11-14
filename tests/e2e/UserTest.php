<?php

namespace Tests;

use App;
use App\Group;
use App\Membership;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class UserTest extends BrowserKitTestCase
{
    /******************* Why is it done this way ? ***************/

    /*
    I want my tests runs on a clean DB, and each test in the right order, like I would do by hand.
    The first tests migrates the testing DB
    Sounds simpler like this for me, I don't want the database being remigrated after each test.
    Only after the whole suite has been run.

    You need a agorakit_testing DB available for those tests to run.


    Our scenario :

    - we have admin, our admin
    - we also have Newbie, another user

    - Roberto creates 2 groups, an open one and a closed one
    - Newbie tries to join both

    What happens ?


    */

    /* Some utility function*/

    public function admin()
    {
        return App\User::where('email', 'admin@agorakit.local')->firstOrFail();
    }

    public function newbie()
    {
        return App\User::where('email', 'newbie@agorakit.local')->firstOrFail();
    }

    public function getTestGroup()
    {
        return App\Group::where('name', 'Test group')->firstOrFail();
    }

    public function getPrivateGroup()
    {
        return App\Group::where('name', 'Private test group')->firstOrFail();
    }

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
            ->type('Test group', 'name')
            ->type('this is a test group', 'body')
            ->press('Create the group')
            ->see('Test group');
    }

    public function testDiscussionCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/discussions/create')
            ->see('Create')
            ->type('Test discussion', 'name')
            ->type('this is a test discussion', 'body')
            ->press('Create')
            ->see('Test discussion');
    }

    public function testEventCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $group = App\Group::where('name', 'Test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/calendarevents/create')
            ->see('Add an event')
            ->type('Test event', 'name')
            ->type('this is a test event in the calendar', 'body')
            ->type('Bruxelles', 'location[city]')
            ->type('2016-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('13:00', 'stop_time')
            ->press('Create')
            ->seeInDatabase('calendar_events', ['name' => 'Test event'])
            ->see(trans('messages.create_event'));
    }

    public function testPrivateGroupCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();

        $this->actingAs($user)
            ->visit('groups/create')
            ->see('Create a new group')
            ->type('Private test group', 'name')
            ->type('this is a test group', 'body')
            ->select('1', 'group_type')
            ->press('Create the group')
            ->see('Private test group')
            ->see('Closed group');
    }

    public function testASecondUserIsRegistering()
    {
        Mail::fake();

        $this->visit('/register')
            ->type('Newbie', 'name')
            ->type('newbie@agorakit.local', 'email')
            ->press('Register')
            ->type('123456789', 'password')
            ->type('123456789', 'password_confirmation')
            ->press('Register')
            ->see('Agorakit');

        $this->seeInDatabase('users', ['email' => 'newbie@agorakit.local']);
    }

    public function testNewbieCanJoinOpenGroup()
    {
        $group = App\Group::where('name', 'Test group')->first();

        $user = App\User::where('email', 'newbie@agorakit.local')->first();

        $user->confirmEmail();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/join')
            ->see('Join the group')
            ->press('Join')
            ->see('Welcome');
    }

    public function testNewbieCantJoinPrivateGroup()
    {
        $group = App\Group::where('name', 'Private test group')->first();

        $user = App\User::where('email', 'newbie@agorakit.local')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/join')
            ->see(trans('membership.apply_for_group'));
    }

    public function testNewbieCanApplyToPrivateGroup()
    {
        $group = App\Group::where('name', 'Private test group')->first();

        $user = App\User::where('email', 'newbie@agorakit.local')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/join')
            ->press(trans('membership.apply'))
            ->see(trans('membership.application_stored'));
    }

    public function testAdminCanConfirmCandidateToPrivateGroup()
    {
        // don't you like this function name?
        $group = App\Group::where('name', 'Private test group')->first();

        $user = App\User::where('email', 'admin@agorakit.local')->first();
        $newbie = App\User::where('email', 'newbie@agorakit.local')->first();

        $this->actingAs($user)
            ->visit('groups/' . $group->id . '/users')
            //->click(trans('messages.confirm_user'))
            ->see(trans('membership.candidate'));

        $membership = \App\Membership::where('user_id', $newbie->id)->where('group_id', $group->id)->first();

        $this->actingAs($user)
            ->visit(route('groups.membership.edit', [$group, $membership]))
            ->select(\App\Membership::MEMBER, 'membership_level')
            ->press(trans('messages.save'))
            ->see(trans('membership.settings_updated'));

        $this->seeInDatabase('membership', ['user_id' => $newbie->id, 'membership' => '20']);
    }

    public function testNewbieCanCreateGroup()
    {
        $user = App\User::where('email', 'newbie@agorakit.local')->first();

        $this->actingAs($user)
            ->visit('groups/create')
            ->see('Create a new group')
            ->type('Test group of newbie', 'name')
            ->type('this is a test group', 'body')
            ->type('Belgique', 'location[country]')
            ->press('Create the group')
            ->see('Test group of newbie');
    }

    public function testRobertoIsAdminOfTestGroup()
    {
        $user = App\User::where('email', 'admin@agorakit.local')->first();
        $group = App\Group::where('name', 'Test group')->first();
        $this->assertTrue($user->isAdminOf($group));
    }

    public function testNewbieIsNotAdminOfTestGroup()
    {
        $user = App\User::where('email', 'newbie@agorakit.local')->first();
        $group = App\Group::where('name', 'Test group')->first();
        $this->assertFalse($user->isAdminOf($group));
    }

    public function testNewbieIsAdminOfTestGroupOfNewbie()
    {
        $user = App\User::where('email', 'newbie@agorakit.local')->first();
        $group = App\Group::where('name', 'Test group of newbie')->first();
        $this->assertTrue($user->isAdminOf($group));
    }

    /* now let's test emails */

    public function testNotificationReceived()
    {
        $group = App\Group::where('name', 'Test group')->firstOrFail();
        $user = App\User::where('email', 'newbie@agorakit.local')->firstOrFail();
        $roberto = App\User::where('email', 'admin@agorakit.local')->firstOrFail();

        // let's first create a discussion in test group that newbie has not read yet, and a long time ago
        $discussion = new \App\Discussion();
        $discussion->name = 'Notify me of this interesting discussion';
        $discussion->body = 'Such an interesting discussion blablbla';
        $discussion->user_id = $roberto->id;
        $discussion->group_id = $group->id;
        $discussion->created_at = '2001-01-01';
        $discussion->total_comments = 1;

        $group->discussions()->save($discussion);

        // fake newbie's membership in order to be in the situation of newbie must be notified
        $membership = App\Membership::where('user_id', $user->id)->where('group_id', $group->id)->firstOrFail();
        $membership->notified_at = '2001-01-01';
        $membership->save();

        // fake our mail sending
        Mail::fake();

        // send notifications if any
        Artisan::call('agorakit:sendnotifications');

        // Assert a message was sent to the given users...
        Mail::assertSent(\App\Mail\Notification::class, function ($mail) use ($user) {
            return $mail->hasTo($user->email);
        });
    }

    /**
     * Admin will disable discussion creation for members.
     */
    public function testNewbieCantChangePermissionsOnGroup()
    {
        $group = $this->getTestGroup();

        $this->actingAs($this->newbie())
            ->get('groups/' . $group->id . '/permissions')
            ->assertResponseStatus(403);

        // using get instead of visit to test responses is documented here : https://laracasts.com/discuss/channels/testing/testing-a-403-response-status-after-submiting-a-form-in-laravel-51?page=1#reply=188868
    }

    /**
     * Admin will disable discussion creation for members.
     */
    public function testChangePermissionsOnGroup()
    {
        $group = $this->getTestGroup();

        $this->actingAs($this->admin())
            ->visit('groups/' . $group->id . '/permissions')
            ->see('Permissions')
            ->check('custom_permissions')
            ->uncheck('member-create-discussion')
            ->uncheck('member-create-file')
            ->uncheck('member-create-calendarevent')
            ->press(trans('messages.save'))
            ->see(trans('messages.ressource_updated_successfully'));
    }

    public function testNewbieCantCreateDiscussionAnymore()
    {
        $group = $this->getTestGroup();

        $this->actingAs($this->newbie())
            ->get('groups/' . $group->id . '/discussions/create')
            ->assertResponseStatus(403);
    }

    public function testNewbieCantCreateEventAnymore()
    {
        $group = $this->getTestGroup();

        $this->actingAs($this->newbie())
            ->get('groups/' . $group->id . '/calendarevents/create')
            ->assertResponseStatus(403);
    }


    public function testNewbieCanBebanned()
    {
        $this->actingAs($this->admin())
            ->visit('users/newbie/edit')
            ->see('Modify')
            ->select('yes', 'is_user_banned')
            ->press(trans('messages.save'))
            ->see(trans('messages.ressource_updated_successfully'));

        $this->seeInDatabase('users', ['email' => 'newbie@agorakit.local', 'is_banned' => 1]);
    }


    public function testNewbieCantLoginAnymore()
    {
        $this->visit('/login')
            ->type('newbie', 'login')
            ->type('123456789', 'password')
            ->press(trans('messages.login'))
            ->see(trans('messages.you_are_banned'));
    }



    public function testUserCantPinGroupIntoNavbar()
    {
        $newbie = $this->newbie();
        $group = Group::where('name', 'Test group of newbie')->firstOrFail();
        $membership = Membership::firstOrNew(['user_id' => $newbie->id, 'group_id' => $group->id]);

        $this->assertEquals($membership->membership, Membership::ADMIN);

        $this->actingAs($newbie)
            ->get('groups/' . $group->id . '/edit')
            ->dontSeeText(trans('group.navbar', ['my_groups' => trans('messages.my_groups')]));
    }

    public function testAdminCanPinGroupIntoNavbar()
    {
        $this->actingAs($this->admin())
            ->visit('groups/' . $this->getTestGroup()->id . '/edit')
            ->seeText(trans('group.navbar', ['my_groups' => trans('messages.my_groups')]))
            ->check('pinned_navbar')
            ->press(trans('messages.save'));

        $group = $this->getTestGroup(); // after set settings because they're updated
        $this->assertTrue($group->settings['pinned_navbar']);
    }
}
