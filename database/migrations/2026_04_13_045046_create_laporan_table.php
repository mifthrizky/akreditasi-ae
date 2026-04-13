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
        Schema::create('laporan', function (Blueprint $table) {
            $table->id('laporan_id');
            $table->foreignId('prodi_id')->constrained('program_studi', 'prodi_id')->onDelete('cascade');
            $table->foreignId('generated_by')->constrained('users', 'user_id')->onDelete('cascade');
            $table->float('skor_total')->nullable();
            $table->string('path_pdf');
            $table->timestamp('generated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};
