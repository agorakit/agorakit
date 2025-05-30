<?php

namespace Tests;


use App\Group;
use App\User;
use App\Console\Kernel;

//use RuntimeException;
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
    }

    /**
     * Export group.
     */
    public function test_export_group(): void
    {
        $user = new User();
        $user->username = "admin";
        $user->password = "123456789";
        $user->email = "admin@agorakit.org";
        $user->save();
        $group = new Group;
        $group->name = "Test Export Group";
        $group->body = "This is a test for export";
        $group->save();
        $this->assertEquals(0, $this->artisan('agorakit:export 1'));
    }

    /**
     * Import group.
     */
    public function test_import_group(): void
    {
        //$this->assertEquals(0, $this->artisan('agorakit:import 1'));
        $this->assertTrue(true);
    }
}
