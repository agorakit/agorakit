<?php

use App\Group;
use App\Membership;

class UserTest extends Tests\BrowserKitTestCase
{
    /******************* Why is it done this way ? ***************/

    /*
    I want my tests runs on a clean DB, and each test in the right order, like I would do by hand.
    The first tests migrates the testing DB
    Sounds simplier like this for me, I don't want the database being remigrated after each test.
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

    const SETTINGS_NAVBAR = [
        'show_overview_inside_navbar',
        'show_overview_all_groups',
        'show_overview_discussions',
        'show_overview_agenda',
        'show_overview_tags',
        'show_overview_map',
        'show_overview_files',
        'show_overview_users',
        'show_locales_inside_navbar',
        'show_locale_fr',
        'show_locale_en',
        'show_locale_nl',
        'show_locale_de',
        'show_locale_es',
        'show_locale_it',
        'show_locale_ru',
        'show_locale_eo',
    ];

    public function admin()
    {
        return App\User::where('email', 'admin@agorakit.org')->firstOrFail();
    }

    public function newbie()
    {
        return App\User::where('email', 'newbie@agorakit.org')->firstOrFail();
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
        Artisan::call('migrate:refresh');

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
            ->type('Test group', 'name')
            ->type('this is a test group', 'body')
            ->press('Create the group')
            ->see('Test group');
    }

    public function testDiscussionCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/discussions/create')
            ->see('Create')
            ->type('Test discussion', 'name')
            ->type('this is a test discussion', 'body')
            ->press('Create')
            ->see('Test discussion');
    }

    public function testActionCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $group = App\Group::where('name', 'Test group')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/actions/create')
            ->see('Add an event')
            ->type('Test action', 'name')
            ->type('this is a test action in the agenda', 'body')
            ->type('Bruxelles', 'location')
            ->type('2016-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('13:00', 'stop_time')
            ->press('Create')
            ->seeInDatabase('actions', ['name' => 'Test action'])
            ->see(trans('action.create_one_button'));
    }

    public function testPrivateGroupCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

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
            ->type('newbie@agorakit.org', 'email')
            ->press('Register')
            ->type('123456789', 'password')
            ->type('123456789', 'password_confirmation')
            ->press('Register')
            ->see('Agorakit');

        $this->seeInDatabase('users', ['email' => 'newbie@agorakit.org']);
    }

    public function testNewbieCanJoinOpenGroup()
    {
        $group = App\Group::where('name', 'Test group')->first();

        $user = App\User::where('email', 'newbie@agorakit.org')->first();

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

        $user = App\User::where('email', 'newbie@agorakit.org')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/join')
            ->see(trans('membership.apply_for_group'));
    }

    public function testNewbieCanApplyToPrivateGroup()
    {
        $group = App\Group::where('name', 'Private test group')->first();

        $user = App\User::where('email', 'newbie@agorakit.org')->first();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/join')
            ->press(trans('membership.apply'))
            ->see(trans('membership.application_stored'));
    }

    public function testAdminCanConfirmCandidateToPrivateGroup()
    {
        // don't you like this function name?
        $group = App\Group::where('name', 'Private test group')->first();

        $user = App\User::where('email', 'admin@agorakit.org')->first();
        $newbie = App\User::where('email', 'newbie@agorakit.org')->first();

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
        $user = App\User::where('email', 'newbie@agorakit.org')->first();

        $this->actingAs($user)
            ->visit('groups/create')
            ->see('Create a new group')
            ->type('Test group of newbie', 'name')
            ->type('this is a test group', 'body')
            ->press('Create the group')
            ->see('Test group of newbie');
    }

    public function testRobertoIsAdminOfTestGroup()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();
        $group = App\Group::where('name', 'Test group')->first();
        $this->assertTrue($user->isAdminOf($group));
    }

    public function testNewbieIsNotAdminOfTestGroup()
    {
        $user = App\User::where('email', 'newbie@agorakit.org')->first();
        $group = App\Group::where('name', 'Test group')->first();
        $this->assertFalse($user->isAdminOf($group));
    }

    public function testNewbieIsAdminOfTestGroupOfNewbie()
    {
        $user = App\User::where('email', 'newbie@agorakit.org')->first();
        $group = App\Group::where('name', 'Test group of newbie')->first();
        $this->assertTrue($user->isAdminOf($group));
    }

    /* now let's test emails */

    public function testNotificationReceived()
    {
        $group = App\Group::where('name', 'Test group')->firstOrFail();
        $user = App\User::where('email', 'newbie@agorakit.org')->firstOrFail();
        $roberto = App\User::where('email', 'admin@agorakit.org')->firstOrFail();

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
            ->uncheck('member-create-action')
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

    public function testNewbieCantCreateActionAnymore()
    {
        $group = $this->getTestGroup();

        $this->actingAs($this->newbie())
        ->get('groups/' . $group->id . '/actions/create')
        ->assertResponseStatus(403);
    }

    public function testNavbarShouldIncludesEverything()
    {
        \App\Setting::query()->delete();

        $this->actingAs($this->newbie())
            ->visit('/')
            ->seeText(__('messages.my_groups'))
            ->seeText(__('messages.overview'))
            ->seeText(__('messages.help'))
            ->seeText('Locale');
    }


    /**
     * @dataProvider dataNavigationBar
     */
    public function testNavbarShouldNotIncludeElement(string $key, string $text)
    {

        \App\Setting::query()->delete();

        $this->actingAs($this->admin())
            ->visit('admin/settings')
            ->click(__('Navigation bar'))
            ->uncheck($key)
            ->press(__('messages.save'));

        $this->assertEquals(0, (new \App\Setting())->get($key));

        $this->actingAs($this->newbie())
            ->get('/discussions')
            ->dontSee($text);
    }

    private function dataNavigationBar(): array
    {
        return [
            'overview' => ['key' => 'show_overview_inside_navbar', 'text' => 'messages.overview',],
            'all groups' => ['key' => 'show_overview_all_groups', 'text' => 'messages.all_groups'],
            'discussions' => ['key' => 'show_overview_discussions', 'text' =>'messages.discussions'],
            'agenda' => ['key' => 'show_overview_agenda', 'text' => 'messages.agenda'],
            'tags' => ['key' => 'show_overview_tags', 'text' => 'messages.tags'],
            'map' => ['key' => 'show_overview_map', 'text' => 'messages.map'],
            'files' => ['key' => 'show_overview_files', 'text' => 'messages.files'],
            'users' => ['key' => 'show_overview_users', 'text' => 'messages.users_list'],
            'locales' => ['key' => 'show_locales_inside_navbar', 'text' => '<!-- locales -->',],
            'locale fr' => ['key' => 'show_locale_fr', 'text' => 'locale-fr',],
            'locale en' => ['key' => 'show_locale_en', 'text' => 'locale-en'],
            'locale nl' => ['key' => 'show_locale_nl', 'text' => 'locale-nl'],
            'locale de' => ['key' => 'show_locale_de', 'text' => 'locale-de'],
            'locale es' => ['key' => 'show_locale_es', 'text' => 'locale-es'],
            'locale it' => ['key' => 'show_locale_it', 'text' => 'locale-it'],
            'locale ru' => ['key' => 'show_locale_ru', 'text' => 'locale-ru'],
            'locale eo' => ['key' => 'show_locale_eo', 'text' => 'locale-eo'],
            'help' => ['key' => 'show_help_inside_navbar', 'text' => 'messages.help'],
        ];
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
