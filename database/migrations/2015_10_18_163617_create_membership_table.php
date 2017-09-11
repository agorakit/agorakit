<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMembershipTable extends Migration
{
    public function up()
    {
        Schema::create('membership', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->integer('user_id')->unsigned()->references('id')->on('users');
            $table->integer('group_id')->unsigned()->references('id')->on('groups');
            $table->unique(['user_id', 'group_id']);

            /*
            * A json string that contains any additional info we'd need for memberships.
            * Not yet in use
            */
            $table->text('config');

            /*
            Membership type
            Look in  \App\Membership constants for help
            */
            $table->tinyInteger('membership')->default(0);

            // number of minutes between notifications asked by the user
            // -1 to disable
            $table->integer('notification_interval');

            // When was the last notification sent ?
            $table->timestamp('notified_at');
        });
    }

    public function down()
    {
        Schema::drop('membership');
    }
}
