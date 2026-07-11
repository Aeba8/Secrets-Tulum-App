<?php

use App\Models\Reserva;
use Carbon\Carbon;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('reservas:completar-vencidas', function () {
    Reserva::whereDate('Dia', '<', Carbon::today())
        ->where('Estado', 'Confirmado')
        ->update(['Estado' => 'Completado']);
    $this->info('Reservas vencidas marcadas como Completado.');
})->purpose('Marcar reservas con fecha pasada como Completado');

Schedule::command('reservas:completar-vencidas')->daily();

Artisan::command('usuarios:hashear-passwords', function () {
    $usuarios = \App\Models\Usuario::all();
    $count = 0;
    foreach ($usuarios as $usuario) {
        if (\Illuminate\Support\Facades\Hash::needsRehash($usuario->Numero_de_colaborador)) {
            $usuario->update([
                'Numero_de_colaborador' => \Illuminate\Support\Facades\Hash::make($usuario->Numero_de_colaborador),
            ]);
            $count++;
        }
    }
    $this->info("$count usuarios migrados a bcrypt.");
})->purpose('Hashear todas las contraseñas legacy de usuarios');
