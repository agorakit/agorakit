<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddItemTypeToFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        // Item type can be :
        // 0 : file (stored on the server)
        // 1 : folder (virtual folders)
        // 2 : link (to an etherpad or google doc for instance)
        Schema::table('files', function ($table) {
            $table->integer('item_type')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('files', function ($table) {
            $table->dropColumn('item_type');
        });
    }
}
