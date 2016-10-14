<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGeoToGroups extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('groups', function ($table) {
            $table->text('address');
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
        Schema::table('groups', function (Blueprint $table)
        {
            $table->dropColumn('address');
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
