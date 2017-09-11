<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateInvitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invites', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('email');
            $table->integer('user_id')->unsigned()->references('id')->on('users');
            $table->integer('group_id')->unsigned()->references('id')->on('groups');
            //Token contains a token generated and sent to users so they can accept an invitation
            $table->string('token');
            $table->timestamp('claimed_at')->nullable();

            // only one invite per user per group
            $table->unique(['email', 'group_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('invites');
    }
}
