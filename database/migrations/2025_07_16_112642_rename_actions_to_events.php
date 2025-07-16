<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('action_user', function (Blueprint $table) {
          $table->renameColumn('action_id', 'calendarevent_id');
        });
        Schema::rename('action_user', 'calendarevent_user');
        Schema::rename('actions', 'calendarevents');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendarevent_user', function (Blueprint $table) {
          $table->renameColumn('calendarevent_id', 'action_id');
        });
        Schema::rename('calendarevent_user', 'action_user');
        Schema::rename('calendarevents', 'actions');
    }
};
