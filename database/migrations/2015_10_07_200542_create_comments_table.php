<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('comments', function(Blueprint $table) {
        $table->increments('id');
        $table->timestamps();
        $table->softDeletes();
        $table->text('body');
        $table->integer('vote');
        $table->integer('discussion_id')->unsigned();
        $table->integer('user_id')->unsigned()->references('id')->on('users');
      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('comments');
    }
}
