<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deceaseds', function (Blueprint $table) {
            // Por defecto 'true' para que los actuales sigan visibles
            $table->boolean('is_active')->default(true)->after('location'); 
        });
    }

    public function down(): void
    {
        Schema::table('deceaseds', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
};