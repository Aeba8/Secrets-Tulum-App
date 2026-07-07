<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fondos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->nullable();
            $table->string('url');
            $table->string('seccion')->nullable()->comment('Ej: cenas, balinesas, experiencias, general');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fondos');
    }
};
