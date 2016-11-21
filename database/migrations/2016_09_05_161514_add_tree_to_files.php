<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
//use Kalnoy\Nestedset\NestedSet;

class AddTreeToFiles extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        /*
        Schema::table('files', function ($table) {
            NestedSet::columns($table);
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

        /*
        Schema::table('files', function ($table) {
            NestedSet::dropColumns($table);
        });
        */

    }
}
