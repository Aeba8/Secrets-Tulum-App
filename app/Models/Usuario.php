<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Importante para la autenticación
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use Notifiable;

    // 1. Apuntamos a tu tabla real
    protected $table = 'Usuarios';
    protected $primaryKey = 'Id';

    // 2. Columnas de tu tabla
    protected $fillable = [
        'Nombre',
        'Email',
        'Numero_de_colaborador',
        'Rol',
    ];

    /**
     * El truco maestro: Laravel por defecto busca una columna llamada 'password'.
     * Al retornar 'Numero_de_colaborador' aquí, Laravel usará ese campo como contraseña interna.
     */
    public function getAuthPassword()
    {
        return $this->Numero_de_colaborador;
    }
}