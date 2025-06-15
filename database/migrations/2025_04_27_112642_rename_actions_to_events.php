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
          $table->renameColumn('action_id', 'event_id');
        });
        Schema::rename('action_user', 'event_user');
        Schema::rename('actions', 'events');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('event_user', function (Blueprint $table) {
          $table->renameColumn('event_id', 'action_id');
        });
        Schema::rename('event_user', 'action_user');
        Schema::rename('events', 'actions');
    }
};
