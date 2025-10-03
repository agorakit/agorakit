<?php

use Illuminate\Database\Migrations\Migration;
use App\User;

return new class extends Migration
{
    /**
     * Create or update the anonymous system user
     */
    public function up(): void
    {
        $anonymous = User::firstOrNew(['email' => 'anonymous@agorakit.org']);
        $anonymous->email = 'anonymous@localhost';
        $anonymous->name = 'Anonymous';
        $anonymous->body = 'Anonymous is a system user';
        $anonymous->verified = 1;
        $anonymous->save();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
