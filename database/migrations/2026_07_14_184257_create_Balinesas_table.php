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
        Schema::create('Balinesas', function (Blueprint $table) {
            $table->increments('Id');
            $table->integer('id_categoria');
            $table->string('Nombre', 150);
            $table->text('Productos')->nullable();
            $table->text('Descripcion')->nullable();
            $table->text('imagenes')->nullable();
            $table->string('Dias', 100)->nullable();
            $table->decimal('Precio', 10);
            $table->decimal('Costo_Operativo', 10)->default(0);
            $table->text('ficha_tecnica')->nullable();
            $table->string('Estado', 20)->default('Activo');
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->decimal('price', 10)->nullable();
            $table->integer('capacidad_maxima')->default(2);
            $table->boolean('is_active')->default(true);
            $table->string('locacion', 100)->nullable();

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Balinesas');
    }
};
