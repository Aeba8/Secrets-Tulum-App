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
        Schema::table('Reservas', function (Blueprint $table) {
            $table->foreign(['id_espacio'], 'FK_Reservas_Espacio')->references(['Id'])->on('Espacios')->onUpdate('no action')->onDelete('no action');
            $table->foreign(['Usuario_id'], 'FK_Reservas_Usuario')->references(['Id'])->on('Usuarios')->onUpdate('no action')->onDelete('no action');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Reservas', function (Blueprint $table) {
            $table->dropForeign('FK_Reservas_Espacio');
            $table->dropForeign('FK_Reservas_Usuario');
        });
    }
};
