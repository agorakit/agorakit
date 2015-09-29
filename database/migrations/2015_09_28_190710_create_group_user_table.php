<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateGroupUserTable extends Migration {

	public function up()
	{
		Schema::create('group_user', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('user_id')->unsigned();
			$table->integer('group_id')->unsigned();


			/*
			Token contains a token generated and sent to users so they can accept an invitation
			*/
			$table->string('token');


			/*
			Membership type (smallint)
			------------------------------
			1 : not confirmed (the user has been invited but didn't reply yet - maybe we need an invitation token)
			2 : subscribed : the user receives emails and notificaions from the group
			3 : the user is an active member of the group

			We could further add more codes like
			-1 : the user doesn't want to be invovled anymore in this group or is blacklisted for some reason
			4 : the user has some superpower (but I want to avoid this)

			*/
			$table->integer('membership')->default(1);





		});
	}

	public function down()
	{
		Schema::drop('group_user');
	}
}
