<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // En lugar de usar factory()->create() directo, validamos si ya existe el email
        User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'), // Le asignamos una contraseña por defecto de forma segura
            ]
        );

        // Llamamos al seeder del catálogo para las tablets
        $this->call([
            HotelCatalogSeeder::class,
        ]);
    }
}