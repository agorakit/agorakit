<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

    //use DatabaseMigrations;


    /******************* Why is it done this way ? ***************/

    /*
    I want my tests runs on a clean DB, and each test in the right order, like I would do by hand.
    The first tests migrates the testing DB
    Sounds simplier like this for me, I don't want the database being remigrated after each test.
    Only after the whole suite has been run.

    You need a mobilizator_testing DB available for those tests to run.

    */

    public function testSetupItAll()
    {
        Artisan::call('migrate:refresh');

        $this->visit('/')
             ->see('Mobilizator');

    }



    /**
    * A basic test example.
    *
    * @return void
    */
    public function testUserRegistration()
    {
        $this->visit('/register')
        ->type('Roberto', 'name')
        ->type('roberto@example.com', 'email')
        ->type('123456', 'password')
        ->type('123456', 'password_confirmation')
        ->press('Register')
        ->seePageIs('');

         $this->seeInDatabase('users', ['email' => 'roberto@example.com']);

    }

    public function testGroupCreation()
    {
        $user = App\User::where('email', 'roberto@example.com')->first();

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
        $user = App\User::where('email', 'roberto@example.com')->first();

        $group = App\Group::where('name', 'Test group')->first();

        $this->actingAs($user)
        ->visit('/groups/'. $group->id .'/discussions/create')
        ->see('Create')
        ->type('Test discussion', 'name')
        ->type('this is a test discussion', 'body')
        ->press('Create')
        ->see('Test discussion');
    }


    public function testActionCreation()
    {
        $user = App\User::where('email', 'roberto@example.com')->first();

        $group = App\Group::where('name', 'Test group')->first();

        $this->actingAs($user)
        ->visit('/groups/'. $group->id .'/actions/create')
        ->see('Create')
        ->type('Test action', 'name')
        ->type('this is a test action in the agenda', 'body')
        ->type('2016-01-01 11:00', 'start')
        ->type('2016-01-01 15:00', 'stop')
        ->press('Create')
        ->see('Test discussion');
    }




}
