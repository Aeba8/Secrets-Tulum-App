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
        Schema::create('Categorias', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 100);
            $table->string('Slug', 100)->unique('uq__categori__bc7b5fb691e3b840');
            $table->string('Icono', 50)->nullable();
            $table->string('Imagen_Fondo')->nullable();

            $table->primary(['Id'], 'pk__categori__3214ec072171af75');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Categorias');
    }
};
