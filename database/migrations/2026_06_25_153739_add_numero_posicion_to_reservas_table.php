<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNumeroPosicionToReservasTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('Reservas', function (Blueprint $table) {
            // Añadimos el campo entero para la posición de la cama (1-20) o mesa (1-4)
            $table->integer('Numero_Posicion')->nullable()->after('serviciable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('Reservas', function (Blueprint $table) {
            // Si hacemos un rollback, eliminamos la columna de SQL Server
            $table->dropColumn('Numero_Posicion');
        });
    }
}