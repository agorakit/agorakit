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


  			/**
  			 * A json string that contains any additional info we'd need for memberships.
  			 * Not yet in use
  			 */
  			$table->text('config');

  			/*
  			Membership type
  			------------------------------
  			20 : subscribed : the user receives emails and notificaions from the group
  			30 : the user is an active member of the group

  			We could further add more codes like
  			-10 : the user doesn't want to be invovled anymore in this group or
  			-20 : the user is blacklisted for some reason (not yet implemented)  			

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
