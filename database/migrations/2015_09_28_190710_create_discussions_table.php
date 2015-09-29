<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateDiscussionsTable extends Migration {

	public function up()
	{
		Schema::create('discussions', function(Blueprint $table) {
			$table->increments('id');
			$table->timestamps();
			$table->softDeletes();
			$table->integer('group_id')->unsigned();
			$table->string('name');
			$table->text('body');
			$table->integer('parent_id')->unsigned();
		});
	}

	public function down()
	{
		Schema::drop('discussions');
	}
}
