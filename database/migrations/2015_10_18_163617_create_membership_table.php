<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembershipTable extends Migration
{

  	public function up()
  	{
  		Schema::create('membership', function(Blueprint $table) {
  			$table->increments('id');
  			$table->timestamps();
  			$table->integer('user_id')->unsigned()->references('id')->on('users');
  			$table->integer('group_id')->unsigned()->references('id')->on('groups');
  			$table->unique(['user_id', 'group_id']);

  			/*
  			Token contains a token generated and sent to users so they can accept an invitation
  			Not yet in use
  			*/
  			$table->string('token');


  			/**
  			 * A json string that contains any additional info we'd need for memberships.
  			 * Not yet in use
  			 */
  			$table->text('config');

  			/*
  			Membership type
  			------------------------------
  			1 : invited but not confirmed (the user has been invited but didn't reply yet - maybe we need an invitation token)
  			2 : subscribed : the user receives emails and notificaions from the group
  			3 : the user is an active member of the group

  			We could further add more codes like
  			-1 : the user doesn't want to be invovled anymore in this group or
  			-2 : the user is blacklisted for some reason
  			4 : the user has some superpower (but I want to avoid this)

  			*/
  			$table->tinyInteger('membership')->default(0);

        // number of minutes between notifications asked by the user
        // -1 to disable
  			$table->integer('notifications');

        // When was the last notification sent
  			$table->timestamp('notified_on');





  		});
  	}

  	public function down()
  	{
  		Schema::drop('membership');
  	}
  }
