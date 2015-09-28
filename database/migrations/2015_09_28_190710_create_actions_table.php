<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActionsTable extends Migration {

	public function up()
	{
		Schema::create('actions', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('group_id')->unsigned();
			$table->string('title');
			$table->text('body');
			$table->datetime('start');
			$table->datetime('stop');
		});
	}

	public function down()
	{
		Schema::drop('actions');
	}
}