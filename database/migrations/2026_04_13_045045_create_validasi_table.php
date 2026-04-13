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
        Schema::create('validasi', function (Blueprint $table) {
            $table->id('validasi_id');
            $table->foreignId('submission_id')->unique()->constrained('submission', 'submission_id')->onDelete('cascade');
            $table->foreignId('validator_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('status', ['disetujui', 'revisi', 'ditolak']);
            $table->text('komentar')->nullable();
            $table->timestamp('validated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validasi');
    }
};
