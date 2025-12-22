<?php

namespace Tests;

use App;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Tests\BaseTest;

class ImportTest extends BaseTest
{
    public function testGroupExport()
    {
        $user = $this->admin();
        $storage = Storage::disk('tmp');
        $group = $this->getTestGroup();

        $this->actingAs($user)
            ->visit('/groups/' . $group->id)
            ->see('Settings')
            ->see('Export Group Data')
            ->click('Export Group Data');

        $files = [];
        foreach ($storage->files('') as $file) {
            $files[$storage->lastModified($file)] = $file;
        }
        $ts = max(array_keys($files));
        // Last file created was no more than 5 seconds ago. @todo Improve this test.
        assert(Carbon::createFromTimestamp($ts)->diffInSeconds(Carbon::now()) < 5);
    }
}
