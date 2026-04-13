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
        Schema::create('submission_item', function (Blueprint $table) {
            $table->id('subitem_id');
            $table->foreignId('submission_id')->constrained('submission', 'submission_id')->onDelete('cascade');
            $table->foreignId('template_item_id')->constrained('template_item', 'template_id')->onDelete('cascade');
            $table->boolean('nilai_checklist')->nullable();
            $table->text('nilai_teks')->nullable();
            $table->float('nilai_numerik')->nullable();
            $table->unique(['submission_id', 'template_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submission_item');
    }
};
