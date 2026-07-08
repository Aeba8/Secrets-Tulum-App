<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('Balinesas', function (Blueprint $table) {
            if (!Schema::hasColumn('Balinesas', 'locacion')) {
                $table->string('locacion', 100)->nullable()->after('Descripcion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('Balinesas', function (Blueprint $table) {
            $table->dropColumn('locacion');
        });
    }
};
