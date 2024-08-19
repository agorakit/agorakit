<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;



class UpdateCharset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::unprepared('ALTER TABLE `discussions` CONVERT TO CHARACTER SET utf8mb4');
            DB::unprepared('ALTER TABLE `comments` CONVERT TO CHARACTER SET utf8mb4');
            DB::unprepared('ALTER TABLE `revisions` CONVERT TO CHARACTER SET utf8mb4');
            DB::unprepared('ALTER TABLE `actions` CONVERT TO CHARACTER SET utf8mb4');
            DB::unprepared('ALTER TABLE `users` CONVERT TO CHARACTER SET utf8mb4');
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $driver = DB::getDriverName();
        if ($driver === 'mysql') {
            DB::unprepared('ALTER TABLE `discussions` CONVERT TO CHARACTER SET utf8');
            DB::unprepared('ALTER TABLE `comments` CONVERT TO CHARACTER SET utf8');
            DB::unprepared('ALTER TABLE `revisions` CONVERT TO CHARACTER SET utf8');
            DB::unprepared('ALTER TABLE `actions` CONVERT TO CHARACTER SET utf8');
            DB::unprepared('ALTER TABLE `users` CONVERT TO CHARACTER SET utf8');
        }
    }
}
