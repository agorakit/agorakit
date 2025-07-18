<?php

use Illuminate\Database\Migrations\Migration;

class FillSlugToGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (\Agorakit\Group::all() as $group) {
            $group->timestamps = false;
            $group->save();
        }
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
