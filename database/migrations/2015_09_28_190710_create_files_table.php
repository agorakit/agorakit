<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFilesTable extends Migration {

	public function up()
	{
		Schema::create('files', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->text('path');
			$table->text('name');
			$table->text('original_filename');
			$table->text('original_extension');
			$table->text('mime');
			$table->integer('user_id')->unsigned()->references('id')->on('users');
			$table->integer('group_id')->unsigned()->index()->references('id')->on('groups');
		});
	}

	public function down()
	{
		Schema::drop('files');
	}
}
