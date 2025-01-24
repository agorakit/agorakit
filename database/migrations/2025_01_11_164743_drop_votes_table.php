<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropVotesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::drop('votes');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->softDeletes();
            $table->integer('user_id')->unsigned()->references('id')->on('users');
            $table->tinyInteger('vote')->default('0');
            $table->integer('comment_id')->unsigned();
        });
    }
};
