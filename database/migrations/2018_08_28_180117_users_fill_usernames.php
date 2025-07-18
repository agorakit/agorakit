<?php

use Illuminate\Database\Migrations\Migration;

class UsersFillUsernames extends Migration
{
    /**
     * Run the migrations : run a save on each user to create a username from their name.
     *
     * @return void
     */
    public function up()
    {
        foreach (\Agorakit\User::all() as $user) {
            $user->timestamps = false;
            $user->save();
        }
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
