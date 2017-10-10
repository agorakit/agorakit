<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFilesizeToFiles extends Migration
{
    public function up()
    {
        // add the filesize column
        Schema::table('files', function ($table) {
                $table->integer('filesize')->unsigned()->default(0)->after('mime');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function ($table) {
            $table->dropColumn('filesize');
        });
    }
}
