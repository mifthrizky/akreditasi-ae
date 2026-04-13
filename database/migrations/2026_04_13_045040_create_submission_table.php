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
        Schema::create('submission', function (Blueprint $table) {
            $table->id('submission_id');
            $table->foreignId('prodi_id')->constrained('program_studi', 'prodi_id')->onDelete('cascade');
            $table->foreignId('kriteria_id')->constrained('kriteria', 'kriteria_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users', 'user_id')->onDelete('cascade');
            $table->enum('status', ['draft', 'submitted', 'diterima', 'revisi', 'ditolak'])->default('draft');
            $table->float('skor')->nullable();
            $table->timestamp('submitted_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['prodi_id', 'kriteria_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission');
    }
};
