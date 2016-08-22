<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUserLocalesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public
    function up()
    {
        Schema::create('ltm_user_locales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id', false, true);
            $table->text('locales')->nullable();
            $table->index(['user_id'], 'ix_ltm_user_locales_user_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public
    function down()
    {
        Schema::drop('ltm_user_locales');
    }
}
