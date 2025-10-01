<?php

namespace Tests;

use App;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Mail;

class FileUploadTest extends BrowserKitTestCase
{
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
            ->type('File upload test group', 'name')
            ->type('this is a test group', 'body')
            ->press('Create the group')
            ->see('File upload test group');
    }

    public function testFolderCreation()
    {
        $user = App\User::where('email', 'admin@agorakit.org')->first();
        $group = App\Group::where('name', 'File upload test group')->firstOrFail();

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
        $group = App\Group::where('name', 'File upload test group')->firstOrFail();

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
}
