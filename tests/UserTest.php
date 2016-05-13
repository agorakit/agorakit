<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

    use DatabaseTransactions;

    /**
    * A basic test example.
    *
    * @return void
    */
    public function testUserRegistration()
    {
        App::setLocale('en');
        $this->visit('/register?force_locale=en')
        ->type('Roberto', 'name')
        ->type('roberto@example.com', 'email')
        ->type('123456', 'password')
        ->type('123456', 'password_confirmation')
        ->press('Register')
        ->seePageIs('');
    }

    public function validateUser()
    {
        App::setLocale('en');
        $user = \App\User::where('email', '=', 'roberto@example.com')->findOrFail();
        $user->confirmEmail();
    }

    public function testGroupCreation()
    {
        App::setLocale('en');

        Session::put('locale', 'en');

        $this->withSession(['locale' => 'en'])
        ->visit('/groups/create?force_locale=en')->see('Create a group');

        $this->withSession(['locale' => 'en'])
        ->visit('/groups/create?force_locale=en')
        ->type('Test group', 'name')
        ->type('this is a test group', 'body')
        ->press('Create the group')
        ->see('Test group');
    }

}
