<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Modificar Cenas_Especiales de forma segura
        Schema::table('Cenas_Especiales', function (Blueprint $table) {
            if (!Schema::hasColumn('Cenas_Especiales', 'name')) $table->string('name')->nullable();
            if (!Schema::hasColumn('Cenas_Especiales', 'slug')) $table->string('slug')->nullable();
            if (!Schema::hasColumn('Cenas_Especiales', 'price')) $table->decimal('price', 10, 2)->nullable();
            if (!Schema::hasColumn('Cenas_Especiales', 'restaurant')) $table->string('restaurant')->nullable();
            if (!Schema::hasColumn('Cenas_Especiales', 'numero_personas')) $table->integer('numero_personas')->default(2);
            if (!Schema::hasColumn('Cenas_Especiales', 'is_active')) $table->boolean('is_active')->default(true);
            if (!Schema::hasColumn('Cenas_Especiales', 'ficha_tecnica')) $table->json('ficha_tecnica')->nullable();
        });

        // 2. Modificar Experiencias de forma segura
        Schema::table('Experiencias', function (Blueprint $table) {
            if (!Schema::hasColumn('Experiencias', 'name')) $table->string('name')->nullable();
            if (!Schema::hasColumn('Experiencias', 'slug')) $table->string('slug')->nullable();
            if (!Schema::hasColumn('Experiencias', 'price')) $table->decimal('price', 10, 2)->nullable();
            if (!Schema::hasColumn('Experiencias', 'tipo')) $table->string('tipo')->nullable();
            if (!Schema::hasColumn('Experiencias', 'lugar')) $table->string('lugar')->nullable();
            if (!Schema::hasColumn('Experiencias', 'duracion')) $table->string('duracion')->nullable();
            if (!Schema::hasColumn('Experiencias', 'horario')) $table->string('horario')->nullable();
            if (!Schema::hasColumn('Experiencias', 'numero_personas')) $table->integer('numero_personas')->default(2);
            if (!Schema::hasColumn('Experiencias', 'is_active')) $table->boolean('is_active')->default(true);
            if (!Schema::hasColumn('Experiencias', 'ficha_tecnica')) $table->json('ficha_tecnica')->nullable();
        });

        // 3. Modificar Balinesas de forma segura
        Schema::table('Balinesas', function (Blueprint $table) {
            if (!Schema::hasColumn('Balinesas', 'name')) $table->string('name')->nullable();
            if (!Schema::hasColumn('Balinesas', 'slug')) $table->string('slug')->nullable();
            if (!Schema::hasColumn('Balinesas', 'price')) $table->decimal('price', 10, 2)->nullable();
            if (!Schema::hasColumn('Balinesas', 'capacidad_maxima')) $table->integer('capacidad_maxima')->default(2);
            if (!Schema::hasColumn('Balinesas', 'is_active')) $table->boolean('is_active')->default(true);
            if (!Schema::hasColumn('Balinesas', 'ficha_tecnica')) $table->json('ficha_tecnica')->nullable();
        });
    }

    public function down(): void
    {
        // Opcional: removerlas si haces rollback
    }
};