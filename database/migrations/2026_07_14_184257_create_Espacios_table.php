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
        Schema::create('Espacios', function (Blueprint $table) {
            $table->increments('Id');
            $table->string('Nombre', 50);
            $table->string('Tipo', 30);
            $table->string('Zona', 50);
            $table->integer('Capacidad')->default(2);
            $table->string('Estado', 20)->default('Disponible');
            $table->boolean('Is_Active')->nullable()->default(true);

            $table->primary(['Id'], 'pk__espacios__3214ec07299f128a');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Espacios');
    }
};
