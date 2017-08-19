<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProviderToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('users', function ($table) {
          $table->string('provider');
          $table->string('provider_id');
          $table->string('email')->nullable()->change();
          $table->string('password', 60)->nullable()->change();
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function ($table) {
          $table->dropColumn('provider');
          $table->dropColumn('provider_id');
        });
    }
}
