<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateReactionsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('reactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('reactable_type')->nullable();
            $table->integer('reactable_id')->nullable();
            $table->string('reactor_type')->nullable();
            $table->integer('reactor_id')->nullable();
            $table->string('context')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('reactions');
    }
}
