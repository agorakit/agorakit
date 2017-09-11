<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddOriginalColumnToLtrTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ltm_translations', function (Blueprint $table) {
            $table->text('saved_value')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ltm_translations', function (Blueprint $table) {
            $table->dropColumn('saved_value');
        });
    }
}
