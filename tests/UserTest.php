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
        ->press('Register');
        ->seePageIs('/home');
    }
}
