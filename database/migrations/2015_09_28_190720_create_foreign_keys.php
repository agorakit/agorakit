<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Model;

class CreateForeignKeys extends Migration {

	public function up()
	{

/*
		->onDelete('restrict')
		->onUpdate('restrict');
	*/

		Schema::table('actions', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users');

		});
		Schema::table('discussions', function(Blueprint $table) {
			$table->foreign('group_id')->references('id')->on('groups');

		});
		Schema::table('discussions', function(Blueprint $table) {
			$table->foreign('parent_id')->references('id')->on('discussions');
		});
		Schema::table('votes', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users');
		});
		Schema::table('files', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users');
		});
		Schema::table('files', function(Blueprint $table) {
			$table->foreign('group_id')->references('id')->on('groups');
		});
		Schema::table('documents', function(Blueprint $table) {
			$table->foreign('user_id')->references('id')->on('users');

		});
		Schema::table('documents', function(Blueprint $table) {
			$table->foreign('group_id')->references('id')->on('groups');
		});
	}

	public function down()
	{
		Schema::table('group_user', function(Blueprint $table) {
			$table->dropForeign('group_user_user_id_foreign');
		});
		Schema::table('group_user', function(Blueprint $table) {
			$table->dropForeign('group_user_group_id_foreign');
		});
		Schema::table('actions', function(Blueprint $table) {
			$table->dropForeign('actions_group_id_foreign');
		});
		Schema::table('discussions', function(Blueprint $table) {
			$table->dropForeign('discussions_group_id_foreign');
		});
		Schema::table('discussions', function(Blueprint $table) {
			$table->dropForeign('discussions_parent_id_foreign');
		});
		Schema::table('votes', function(Blueprint $table) {
			$table->dropForeign('votes_user_id_foreign');
		});
		Schema::table('files', function(Blueprint $table) {
			$table->dropForeign('files_user_id_foreign');
		});
		Schema::table('files', function(Blueprint $table) {
			$table->dropForeign('files_group_id_foreign');
		});
		Schema::table('documents', function(Blueprint $table) {
			$table->dropForeign('documents_user_id_foreign');
		});
		Schema::table('documents', function(Blueprint $table) {
			$table->dropForeign('documents_group_id_foreign');
		});
	}
}
