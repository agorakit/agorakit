<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ChangeSrcReferenceColumnInTranslations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ltm_translations', function (Blueprint $table) {
            $table->dropColumn('source');
        });

        Schema::table('ltm_translations', function (Blueprint $table) {
            $table->text('source')->nullable();
            $table->boolean('is_auto_added')->default(0);
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
            $table->dropColumn('source');
            $table->dropColumn('is_auto_added');
        });

        Schema::table('ltm_translations', function (Blueprint $table) {
            $table->string('source', 256)->nullable();
        });
    }
}
