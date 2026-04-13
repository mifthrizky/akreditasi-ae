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
        Schema::create('dokumen', function (Blueprint $table) {
            $table->id('dokumen_id');
            $table->foreignId('subitem_id')->constrained('submission_item', 'subitem_id')->onDelete('cascade');
            $table->string('nama_file');
            $table->string('path_file');
            $table->string('tipe_file', 50);
            $table->integer('ukuran_file');
            $table->timestamp('uploaded_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dokumen');
    }
};
