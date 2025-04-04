<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /*  Schema::table('users', function (Blueprint $table) {
            $table->text('body')->nullable()->default(null)->change();
            $table->text('preferences')->nullable()->default(null)->change();
            $table->text('address')->nullable()->default(null)->change();
            $table->float('latitude', 10, 7)->nullable()->default(null)->change();
            $table->float('longitude', 10, 7)->nullable()->default(null)->change();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('locale')->nullable()->default(null)->change();
        });*/

        Schema::table('groups', function (Blueprint $table) {
            $table->string('cover')->nullable()->default(null)->change();
            $table->string('color')->nullable()->default(null)->change();
            $table->text('settings')->nullable()->default(null)->change();
            $table->text('address')->nullable()->default(null)->change();
            $table->float('latitude', 10, 7)->nullable()->default(null)->change();
            $table->float('longitude', 10, 7)->nullable()->default(null)->change();
        });

        Schema::table(
            'membership',
            function (Blueprint $table) {
                $table->text('config')->nullable()->default(null)->change();
                $table->timestamp('notified_at')->nullable()->default(null)->change();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->string('cover')->nullable(false)->default(null)->change();
            $table->string('color')->nullable(false)->default(null)->change();
            $table->text('settings')->nullable(false)->default(null)->change();
            $table->text('address')->nullable(false)->default(null)->change();
            $table->float('latitude', 10, 7)->nullable(false)->default(null)->change();
            $table->float('longitude', 10, 7)->nullable(false)->default(null)->change();
        });
        Schema::table('settings', function (Blueprint $table) {
            $table->string('locale')->nullable(false)->default(null)->change();
        });

        Schema::table(
            'membership',
            function (Blueprint $table) {
                $table->text('config')->nullable(false)->default(null)->change();
                $table->timestamp('notified_at')->nullable(false)->default(null)->change();
            }
        );
    }
};
