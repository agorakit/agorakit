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
        Schema::table('groups', function (Blueprint $table) {
          $table->renameColumn('address', 'location');
        });
        Schema::table('users', function (Blueprint $table) {
          $table->renameColumn('address', 'location');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('groups', function (Blueprint $table) {
          $table->renameColumn('location', 'address');
        });
        Schema::table('users', function (Blueprint $table) {
          $table->renameColumn('location', 'address');
        });
    }
};
