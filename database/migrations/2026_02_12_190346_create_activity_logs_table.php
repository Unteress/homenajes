<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete(); // El culpable
            $table->string('action'); // 'created', 'updated', 'deleted'
            $table->string('model_type'); // 'App\Models\Deceased', etc.
            $table->unsignedBigInteger('model_id'); // El ID del registro afectado
            $table->json('details')->nullable(); // Qué cambió (opcional, pero muy útil)
            $table->string('ip_address')->nullable(); // Desde dónde
            $table->timestamps(); // Cuándo
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};