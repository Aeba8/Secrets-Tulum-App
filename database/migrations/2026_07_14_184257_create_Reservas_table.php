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
        Schema::create('Reservas', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Numero_de_colaborador_vendedor', 20);
            $table->integer('Usuario_id');
            $table->string('Habitacion', 10);
            $table->integer('id_espacio');
            $table->date('Dia');
            $table->string('Estado', 20)->default('Activa');
            $table->text('Observaciones')->nullable();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->dateTime('updated_at')->nullable()->useCurrent();
            $table->integer('serviciable_id');
            $table->string('serviciable_type');
            $table->integer('Numero_Posicion')->nullable();

            $table->primary(['Id'], 'pk__reservas__3214ec076c4aa8a3');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Reservas');
    }
};
