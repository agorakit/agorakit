<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddGroupIndexToTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ltm_translations', function (Blueprint $table) {
            $table->index(['group'], 'ix_ltm_translations_group');
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
            $table->dropIndex('ix_ltm_translations_group');
        });
    }
}
