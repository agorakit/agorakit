<?php

namespace Tests;

use App;
use Tests\BaseTest;

class SearchTest extends BaseTest
{
    public function testSearchWithoutQuery()
    {
        $this->actingAs($this->admin())
            ->get('search')
            ->assertResponseOk();
    }

    public function testSearchWithEmptyQuery()
    {
        $this->actingAs($this->admin())
            ->get('search?query=')
            ->assertResponseOk();
    }

    public function testAdminCanSearchAllGroups()
    {
        $this->actingAs($this->admin())
            ->visit('search?query=test&type=groups&scope=admin')
            ->see('Private test group');
    }

    public function testRegularUserCannotUseAdminScope()
    {
        $user = App\User::where('email', 'searcher@agorakit.local')->first() ?? new App\User();
        $user->forceFill([
            'name' => 'Searcher',
            'username' => 'searcher',
            'email' => 'searcher@agorakit.local',
            'password' => bcrypt('123456789'),
        ])->save();
        $user->confirmEmail();

        $this->assertFalse($user->isAdmin());

        $this->actingAs($user)
            ->visit('search?query=test&type=groups&scope=admin')
            ->dontSee('Private test group');
    }

    public function testSearchWithPublicContextPreference()
    {
        $user = App\User::where('email', 'searcher@agorakit.local')->firstOrFail();
        $user->setPreference('show', 'public');

        $this->actingAs($user)
            ->visit('search?query=test&type=groups')
            ->see('Test group');
    }
}
