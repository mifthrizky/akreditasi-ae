<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key constraint first
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['submission_id']);
        });
        
        // Make submission_id nullable
        DB::statement('ALTER TABLE audit_logs ALTER COLUMN submission_id DROP NOT NULL');
        
        // Re-add foreign key constraint with nullable
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->foreign('submission_id')
                ->references('submission_id')
                ->on('submission')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key constraint
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropForeign(['submission_id']);
        });
        
        // Make submission_id NOT NULL again
        DB::statement('ALTER TABLE audit_logs ALTER COLUMN submission_id SET NOT NULL');
        
        // Re-add foreign key constraint
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->foreign('submission_id')
                ->references('submission_id')
                ->on('submission')
                ->onDelete('cascade');
        });
    }
};
