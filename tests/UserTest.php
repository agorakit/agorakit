<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

    use DatabaseMigrations;

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

        $this->actingAs($user)
        ->visit('/groups/create')
        ->see('Create a new group');

        $this->visit('/groups/create')
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
        ->see('Create a new discussion');

        $this->visit('/groups/create')
        ->type('Test group', 'name')
        ->type('this is a test group', 'body')
        ->press('Create the group')
        ->see('Test group');
    }




}
