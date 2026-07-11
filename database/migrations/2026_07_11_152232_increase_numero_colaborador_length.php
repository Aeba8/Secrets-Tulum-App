<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE [Usuarios] ALTER COLUMN [Numero_de_colaborador] VARCHAR(255) NOT NULL');
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE [Usuarios] ALTER COLUMN [Numero_de_colaborador] VARCHAR(6) NOT NULL');
    }
};
