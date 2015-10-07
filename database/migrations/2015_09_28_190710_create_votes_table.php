<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateVotesTable extends Migration {

	public function up()
	{
		Schema::create('votes', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('user_id')->unsigned()->references('id')->on('users');
			$table->tinyInteger('vote')->default('0');
			$table->string('votable_type');
			$table->integer('votable_id')->unsigned();
			$table->boolean('is_spam')->default(false);
		});
	}

	public function down()
	{
		Schema::drop('votes');
	}
}
