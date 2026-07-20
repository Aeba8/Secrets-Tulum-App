<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(
            ['Clave' => 'terminos_y_condiciones'],
            [
                'Valor' => 'Al confirmar esta reservación, el usuario acepta que la reserva es intransferible y sujeta a disponibilidad. El hotel se reserva el derecho de modificar o cancelar la reservación por motivos operativos. Se aplican las políticas de cancelación vigentes del establecimiento.',
            ]
        );
    }
}
