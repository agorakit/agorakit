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
			$table->integer('user_id')->unsigned();
			$table->integer('group_id')->unsigned()->index();
		});
	}

	public function down()
	{
		Schema::drop('files');
	}
}