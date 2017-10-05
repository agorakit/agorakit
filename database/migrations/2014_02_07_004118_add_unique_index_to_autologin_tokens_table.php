<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueIndexToAutologinTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('autologin_tokens', function (Blueprint $table) {
            $table->dropIndex('autologin_tokens_token_index');

            $table->unique('token');
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
            $table->dropUnique('autologin_tokens_token_unique');

            $table->index('token');
        });
    }
}
