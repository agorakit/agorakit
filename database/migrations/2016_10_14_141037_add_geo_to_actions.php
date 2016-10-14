<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeoToActions extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('actions', function ($table) {
            $table->float('latitude', 10, 7);
            $table->float('longitude', 10, 7);
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::table('actions', function (Blueprint $table)
        {
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
