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
        Schema::table('Balinesas', function (Blueprint $table) {
            $table->foreign(['id_categoria'], 'FK_Balinesas_Categoria')->references(['Id'])->on('Categorias')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Balinesas', function (Blueprint $table) {
            $table->dropForeign('FK_Balinesas_Categoria');
        });
    }
};
