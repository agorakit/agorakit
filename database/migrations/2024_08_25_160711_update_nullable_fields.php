<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNullableFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('body')->nullable()->default(null)->change();
            $table->text('preferences')->nullable()->default(null)->change();
            $table->text('address')->nullable()->default(null)->change();
            $table->float('latitude', 10, 7)->nullable()->default(null)->change();
            $table->float('longitude', 10, 7)->nullable()->default(null)->change();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('locale')->nullable()->default(null)->change();
        });
        Schema::table('groups', function (Blueprint $table) {
            $table->string('cover')->nullable()->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Setting the default to `null` after making the column not-nullable removes the default.
            // @see https://stackoverflow.com/questions/38351498/remove-default-in-migration
            $table->text('body')->nullable(false)->default(null)->change();
            $table->text('preferences')->nullable(false)->default(null)->change();
            $table->text('address')->nullable(false)->default(null)->change();
            $table->float('latitude', 10, 7)->nullable(false)->default(null)->change();
            $table->float('longitude', 10, 7)->nullable(false)->default(null)->change();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('locale')->nullable(false)->default(null)->change();
        });
        Schema::table('groups', function (Blueprint $table) {
            $table->text('cover')->nullable(false)->default(null)->change();
        });
    }
}
