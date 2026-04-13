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
        Schema::create('kriteria', function (Blueprint $table) {
            $table->id('kriteria_id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->string('kode', 10)->unique();
            $table->string('nama', 255);
            $table->text('deskripsi')->nullable();
            $table->integer('level')->default(0);
            $table->float('bobot');
            $table->integer('urutan');
            $table->foreign('parent_id')->references('kriteria_id')->on('kriteria')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriteria');
    }
};
