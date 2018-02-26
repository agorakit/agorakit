<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSoftDeleteToMembership extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::table('membership', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        //
    }
}
