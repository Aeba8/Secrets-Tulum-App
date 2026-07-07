<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Cenas_Especiales', function (Blueprint $table) {
            if (!Schema::hasColumn('Cenas_Especiales', 'imagenes')) {
                $table->json('imagenes')->nullable();
            }
        });

        Schema::table('Experiencias', function (Blueprint $table) {
            if (!Schema::hasColumn('Experiencias', 'imagenes')) {
                $table->json('imagenes')->nullable();
            }
        });

        Schema::table('Balinesas', function (Blueprint $table) {
            if (!Schema::hasColumn('Balinesas', 'imagenes')) {
                $table->json('imagenes')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('Cenas_Especiales', function (Blueprint $table) {
            $table->dropColumn('imagenes');
        });
        Schema::table('Experiencias', function (Blueprint $table) {
            $table->dropColumn('imagenes');
        });
        Schema::table('Balinesas', function (Blueprint $table) {
            $table->dropColumn('imagenes');
        });
    }
};
