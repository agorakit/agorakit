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
        $this->visit('/auth/register')
        ->type('Roberto', 'name')
        ->type('roberto@example.com', 'email')
        ->type('123456', 'password')
        ->type('123456', 'password_confirmation')
        ->press('Register')
        ->seePageIs('/home');
    }

    public function validateUser()
    {
      $user = \App\User::where('email', '=', 'roberto@example.com')->findOrFail();
      $user->confirmEmail();
    }

    public function testGroupCreation()
    {
      $this->assertTrue(Auth::attempt(['email' => 'roberto@example.com', 'password' => '123456'], true));

      App::setLocale('en');
      $this->visit('/groups/create')->see('Create a group');
      $this->type('Test group', 'name')
      ->type('this is a test group', 'body')
      ->press('Create')
      ->see('Test group');
    }

}
