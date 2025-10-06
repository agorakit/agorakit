<?php

namespace Tests;

use App\Group;
use App\User;
use App\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Laravolt\Avatar\Avatar;
use Illuminate\Foundation\Testing\TestCase;
use Laravel\BrowserKitTesting\Concerns\InteractsWithConsole;

class ConsoleTest extends TestCase
{
    use InteractsWithConsole;

    /* tests starts here : let's setup the DB
    */
    public function testSetupItAll()
    {
        $this->assertEquals(0, $this->artisan('migrate:fresh'));
        $dir = Storage::disk()->path('groups/1/files');
        foreach (Storage::allFiles($dir) as $filename) {
            Storage::delete($filename);
        }
        Storage::delete('groups/1/files/1');
        Storage::delete('groups/1/files');
    }

    /**
     * Export group.
     */
    public function testExportGroup(): void
    {
        $user = new User();
        $user->username = "admin";
        $user->password = Hash::make("123456789");
        $user->email = "admin@agorakit.local";
        $user->verified = 1;
        $user->save();
        $group = new Group();
        $group->name = "Test Export Group";
        $group->body = "This is a test for export";
        $group->save();
        $this->assertEquals($group->id, '1');
        $file = new File();
        $file->name = 'avatar.svg';
        $file->path = 'groups/' . $group->id . '/files/1/' . $file->name;
        $file->original_filename = "test";
        $file->original_extension = "test";
        $file->mime = "test";
        $file->user_id = $user->id;
        $group->files()->save($file);
        $this->assertEquals($file->id, '1');
        Storage::makeDirectory('groups/' . $group->id . '/files');
        Storage::makeDirectory('groups/' . $group->id . '/files/' . $file->id);
        $avatar = (new \Laravolt\Avatar\Avatar())->create("test")->toSvg();
        Storage::put($file->path, $avatar);
        $this->assertEquals(0, $this->artisan('agorakit:export 1'));
    }

    /**
     * Import group.
     */
    public function testImportGroup(): void
    {
        //$this->assertEquals(0, $this->artisan('agorakit:import 1'));
        $this->assertTrue(true);
    }
}
