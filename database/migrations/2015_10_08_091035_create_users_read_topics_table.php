<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

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

        Schema::create('user_read_discussion', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->references('id')->on('users');
            $table->integer('discussion_id')->unsigned()->references('id')->on('discussions');
            $table->integer('read_comments')->unsigned();
            $table->dateTime('read_at'); // candidate for suppression

            $table->unique(['user_id', 'discussion_id']);
            $table->index('user_id');
            $table->index('discussion_id');
            $table->index('read_at'); // candidate for suppression
            $table->index('read_comments');
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
