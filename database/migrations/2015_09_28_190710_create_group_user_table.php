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
		});
	}

	public function down()
	{
		Schema::drop('group_user');
	}
}