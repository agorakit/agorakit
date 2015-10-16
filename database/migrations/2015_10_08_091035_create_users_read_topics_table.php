<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersReadTopicsTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {

        // inspiration from : https://www.reddit.com/r/laravel/comments/2l2ndq/unread_forum_posts/

        Schema::create('user_read_discussion', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users');
            $table->integer('discussion_id')->unsigned()->references('id')->on('discussions');
            $table->dateTime('read_at');

            $table->unique(['user_id', 'discussion_id']);
        });
    }


    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('user_read_discussion');
    }
}
