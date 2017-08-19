<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialProfiles extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {

        Schema::create('social_profiles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('provider');
            $table->string('provider_id');
            $table->timestamps();

            $table->unique(['user_id', 'provider_id']); // a user can only have one instance of each provider
            $table->unique(['provider', 'provider_id']); // only one sign on per provider per user

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
        /*
        Schema::table('users', function ($table) {
            $table->string('provider');
            $table->string('provider_id');
            $table->string('email')->nullable()->change();
            $table->string('password', 60)->nullable()->change();
        });
        */



    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::drop('social_profiles');
    }
}
