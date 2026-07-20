<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tables = ['Balinesas', 'Cenas_Especiales', 'Experiencias', 'Espacios'];
        foreach ($tables as $table) {
            DB::statement("ALTER TABLE \"{$table}\" ADD COLUMN IF NOT EXISTS \"Orden\" INTEGER DEFAULT 0");
            DB::statement("UPDATE \"{$table}\" SET \"Orden\" = \"Id\" WHERE \"Orden\" IS NULL OR \"Orden\" = 0");
        }
    }

    public function down(): void
    {
        $tables = ['Balinesas', 'Cenas_Especiales', 'Experiencias', 'Espacios'];
        foreach ($tables as $table) {
            DB::statement("ALTER TABLE \"{$table}\" DROP COLUMN IF EXISTS \"Orden\"");
        }
    }
};
