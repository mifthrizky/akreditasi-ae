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
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('user_id');
            $table->string('action'); // created, updated, submitted, approved, rejected, etc.
            $table->json('changed_fields')->nullable(); // Array of field names that changed
            $table->json('old_values')->nullable(); // Old values of changed fields
            $table->json('new_values')->nullable(); // New values of changed fields
            $table->timestamp('created_at')->useCurrent();

            // Foreign keys
            $table->foreign('submission_id')
                ->references('submission_id')
                ->on('submission')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('user_id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
