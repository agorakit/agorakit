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
          $table->renameColumn('action_id', 'calendar_event_id');
        });
        Schema::rename('action_user', 'calendar_event_user');
        Schema::rename('actions', 'calendar_events');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('calendar_event_user', function (Blueprint $table) {
          $table->renameColumn('calendar_event_id', 'action_id');
        });
        Schema::rename('calendar_event_user', 'action_user');
        Schema::rename('calendar_events', 'actions');
    }
};
