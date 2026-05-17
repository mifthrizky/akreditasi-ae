<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // PostgreSQL: Drop existing check constraint and create new one
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'dosen', 'validator', 'super_admin'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove super_admin users first to avoid constraint error
        DB::table('users')->where('role', 'super_admin')->delete();
        
        // Restore original constraint
        DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
        DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin', 'dosen', 'validator'))");
    }
};
