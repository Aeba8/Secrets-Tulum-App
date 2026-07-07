<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Usuario;
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

        // 👤 Usuario Administrador para el Panel de Administración A&B
        Usuario::firstOrCreate(
            ['Numero_de_colaborador' => 'ADMIN001'],
            [
                'Rol' => 'Admin',
                'Nombre' => 'Admin Principal',
                'Email' => 'admin@secretspad.com',
                'Numero_de_colaborador' => 'ADMIN001',
            ]
        );

        // 👤 Usuario Operativo de ejemplo para las iPads
        Usuario::firstOrCreate(
            ['Numero_de_colaborador' => 'OP001'],
            [
                'Rol' => 'Operativo',
                'Nombre' => 'Operativo Demo',
                'Email' => 'operativo@secretspad.com',
                'Numero_de_colaborador' => 'OP001',
            ]
        );

        // Llamamos al seeder del catálogo para las tablets
        $this->call([
            HotelCatalogSeeder::class,
        ]);
    }
}