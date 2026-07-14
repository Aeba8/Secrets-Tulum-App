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
        Schema::create('Usuarios', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Rol', 50);
            $table->string('Nombre', 150);
            $table->string('Numero_de_colaborador')->unique('uq__usuarios__0cbc390f85a85463');
            $table->string('Email', 150)->nullable();
            $table->string('Estado', 10)->nullable()->default('Activo');

            $table->primary(['Id'], 'pk__usuarios__3214ec075f88ab63');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Usuarios');
    }
};
