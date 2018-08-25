<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAnonymousUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        $anonymous = \App\User::firstOrNew(['email' => 'anonymous@agorakit.org']);
        $anonymous->name = 'Anonymous';
        $anonymous->body = 'Anonymous is a system user';
        $anonymous->verified = 1;

        $anonymous->save();

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
