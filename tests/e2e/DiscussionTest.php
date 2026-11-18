<?php

namespace Tests;

use App;
use Tests\BaseTest;

class DiscussionTest extends BaseTest
{
    public function testGuestsHaveNoReadStatus()
    {
        $discussion = App\Discussion::firstOrFail();

        $this->assertCount(0, $discussion->userReadDiscussion);
        $this->assertCount(0, App\Discussion::with('userReadDiscussion')->findOrFail($discussion->id)->userReadDiscussion);
    }

    public function testMembersSeeTheirOwnReadStatus()
    {
        $admin = $this->admin();
        $discussion = App\Discussion::firstOrFail();

        $this->actingAs($admin);
        $discussion->markAsRead();

        $readStatus = App\Discussion::with('userReadDiscussion')->findOrFail($discussion->id)->userReadDiscussion;
        $this->assertCount(1, $readStatus);
        $this->assertEquals($admin->id, $readStatus->first()->user_id);
    }
}
