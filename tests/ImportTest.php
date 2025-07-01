<?php

use App\Group;
use App\Membership;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class ImportTest extends Tests\BrowserKitTestCase
{
    /******************* Why is it done this way ? ***************/

    /*
    I want my tests runs on a clean DB, and each test in the right order, like I would do by hand.
    The first tests migrates the testing DB
    Sounds simplier like this for me, I don't want the database being remigrated after each test.
    Only after the whole suite has been run.

    You need a agorakit_testing DB available for those tests to run.


    Our scenario :

    - make 3 groups, like in  UserTest.php
    - add a file and an action
    - export Test group to a zip file
    - forcibly delete Test group
    - import the zip file
    - compare

    private $export;

    /**
     * Setup the DB
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
            ->type('Test group', 'name')
            ->type('this is a test group', 'body')
            ->press('Create the group')
            ->see('Test group');
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

        //let's create a second discussion in test group
        $newbie = App\User::where('email', 'newbie@agorakit.org')->firstOrFail();
        $discussion = new \App\Discussion();
        $discussion->name = 'Notify me of this interesting discussion';
        $discussion->body = 'Such an interesting discussion blablbla';
        $discussion->user_id = $newbie->id;
        $discussion->group_id = $group->id;
        $discussion->created_at = '2001-01-01';
        $discussion->total_comments = 1;

        $group->discussions()->save($discussion);
        $discussion->tag('test');

        $this->actingAs($newbie)
            ->visit('/groups/' . $group->id . '/discussions')
            ->click('Test discussion')
            ->type('Test comment', 'body')
            ->press('Reply')
            ->see('Newbie')
            ->see('Test comment');
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
            ->type('Bruxelles', 'location[city]')
            ->type('2016-01-01', 'start_date')
            ->type('12:00', 'start_time')
            ->type('13:00', 'stop_time')
            ->press('Create')
            ->see(trans('messages.create_action'));
    }

    public function testFolderCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();
        $group = App\Group::where('name', 'Test group')->firstOrFail();

        $this->actingAs($user)
          ->visit('/groups/' . $group->id . '/files/createfolder')
          ->see('Create a folder')
          ->type('Test folder', 'name')
          ->press('Create')
          ->see('Test folder');
    }

    public function testFileUpload()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();
        $group = App\Group::where('name', 'Test group')->firstOrFail();

        $file = UploadedFile::fake()->image('avatar.jpg');
        $pathname = stream_get_meta_data($file->tempFile)['uri'];

        $this->actingAs($user)
          ->visit('/groups/' . $group->id . '/files/create')
          ->see('Upload file')
          ->attach($pathname, 'file')
          ->press('Create')
          ->see('Resource created successfully')
          ->see('Upload File');
    }

    public function testPrivateGroupCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();

        $this->actingAs($user)
            ->visit('groups/create')
            ->see('Create a new group')
            ->type('Private group', 'name')
            ->type('this is a private group', 'body')
            ->select('1', 'group_type')
            ->press('Create the group')
            ->see('Private group')
            ->see('Closed group');
    }


    public function testNewbieCanCreateGroup()
    {
        $user = App\User::where('email', 'newbie@agorakit.org')->first();

        $this->actingAs($user)
            ->visit('groups/create')
            ->see('Create a new group')
            ->type('Newbie group', 'name')
            ->type('this is a newbie group', 'body')
            ->type('Belgique', 'location[country]')
            ->press('Create the group')
            ->see('Newbie group');
    }

    public function testGroupExport()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();
        $group = App\Group::where('name', 'Test group')->firstOrFail();
        $storage = Storage::disk('tmp');
        global $export;

        $this->actingAs($user)
          ->visit('/groups/1')
          ->see('Settings')
          ->see('Export Group Data')
          ->click('Export Group Data');

        $files = array();
        foreach($storage->files('') as $file) {
            $files[$storage->lastModified($file)] = $file;
        }
        $ts = max(array_keys($files));
        $export = $files[$ts];
        assert(Carbon::createFromTimestamp($ts)->diffInSeconds(Carbon::now()) < 1);
    }

    public function testGroupImport()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();
        $group = App\Group::where('name', 'Test group')->firstOrFail();
        $storage = Storage::disk('tmp');
        global $export;
        $file = fopen($storage->path($export), 'r');
        $import = stream_get_meta_data($file)['uri'];

        $group->forceDelete();

        $this->actingAs($user)
            ->visit('/groups')
            ->dontSee('Test group')
            ->click('Start a group')
            ->see('Create a new group')
            ->click('Import from a File')
            ->see('Import a Group')
            ->attach($import, 'import')
            ->press('Import')
            ->see('Importing a Group')
            ->press('Create the group')
            ->see('Test group (imported)');
    }
}
