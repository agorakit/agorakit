<?php

use Illuminate\Database\Migrations\Migration;

class AddParentIdToFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('files', function ($table) {
            if (!Schema::hasColumn('files', 'parent_id')) {
                $table->integer('parent_id')->nullable();
            }
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
            $table->dropColumn('parent_id');
        });
    }
}
