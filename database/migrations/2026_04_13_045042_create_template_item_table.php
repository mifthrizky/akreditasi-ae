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
        Schema::create('template_item', function (Blueprint $table) {
            $table->id('template_id');
            $table->foreignId('kriteria_id')->constrained('kriteria', 'kriteria_id')->onDelete('cascade');
            $table->enum('tipe', ['checklist', 'upload', 'numerik', 'narasi']);
            $table->string('label');
            $table->string('hint')->nullable();
            $table->boolean('wajib')->default(true);
            $table->float('bobot')->default(0);
            $table->float('nilai_min_numerik')->nullable();
            $table->integer('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('template_item');
    }
};
