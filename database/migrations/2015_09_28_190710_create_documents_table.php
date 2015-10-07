<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDocumentsTable extends Migration {

	public function up()
	{
		Schema::create('documents', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->string('name');
			$table->text('body');
			$table->integer('user_id')->unsigned()->references('id')->on('users');
			$table->integer('group_id')->unsigned()->references('id')->on('groups');
		});
	}

	public function down()
	{
		Schema::drop('documents');
	}
}
