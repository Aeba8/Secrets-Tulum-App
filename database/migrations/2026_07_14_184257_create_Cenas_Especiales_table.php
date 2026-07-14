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
        Schema::create('Cenas_Especiales', function (Blueprint $table) {
            $table->increments('Id');
            $table->integer('id_categoria');
            $table->string('Nombre', 150);
            $table->text('imagenes')->nullable();
            $table->string('Entrada')->nullable();
            $table->string('Crema')->nullable();
            $table->string('Plato_fuerte')->nullable();
            $table->string('Postre')->nullable();
            $table->decimal('Precio', 10);
            $table->decimal('Costo_Operativo', 10)->default(0);
            $table->text('ficha_tecnica')->nullable();
            $table->string('Estado', 20)->default('Activo');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->decimal('price', 10)->nullable();
            $table->string('restaurant')->nullable();
            $table->integer('numero_personas')->default(2);
            $table->boolean('is_active')->default(true);

            $table->primary(['Id'], 'pk__cenas_es__3214ec07dc2d7835');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Cenas_Especiales');
    }
};
