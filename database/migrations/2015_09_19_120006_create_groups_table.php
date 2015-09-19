<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('group_user', function (Blueprint $table) {
          $table->integer('user_id')->unsigned()->index();
          $table->foreign('user_id')->references('id')->on('users');

          $table->integer('group_id')->unsigned()->index();
          $table->foreign('group_id')->references('id')->on('groups');

          $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('groups');
        Schema::drop('group_user');
    }
}
