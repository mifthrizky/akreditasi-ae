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
        Schema::create('page_permissions', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->unique()->comment('Route name (e.g., admin.dashboard, dosen.prodi.index)');
            $table->string('page_label')->comment('Human-readable page label (e.g., Admin Dashboard)');
            $table->json('allowed_roles')->default('[]')->comment('JSON array of roles: ["admin", "dosen", "validator"]');
            $table->text('description')->nullable()->comment('Description of what this page does');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('page_permissions');
    }
};
