<?php

namespace Tests;

use App;
use Illuminate\Http\UploadedFile;
use Tests\BaseTest;

class FileUploadTest extends BaseTest
{
    public function testFileUpload()
    {
        $user = $this->admin();
        $group = $this->getTestGroup();


        $file = UploadedFile::fake()->image('avatar.jpg');
        $pathname = stream_get_meta_data($file->tempFile)['uri'];

        $this->actingAs($user)
            ->visit('/groups/' . $group->id . '/files/create')
            ->see('Upload file')
            ->attach($pathname, 'file')
            ->press('Create')
            ->see('Resource created successfully')
            ->see('Upload File');
    }
}
