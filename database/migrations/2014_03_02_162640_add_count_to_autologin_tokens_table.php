<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddCountToAutologinTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('autologin_tokens', function (Blueprint $table) {
            $table->integer('count')->default(0)->nullable()->after('path');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('autologin_tokens', function (Blueprint $table) {
            $table->dropColumn('count');
        });
    }
}
