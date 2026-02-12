<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('deceased_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('deceased_id')->constrained()->onDelete('cascade');
            $table->string('path');
            $table->string('type')->default('gallery'); 
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('deceased_photos');
    }
};