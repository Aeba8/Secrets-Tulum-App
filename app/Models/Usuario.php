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

    public $timestamps = false;

    protected $fillable = [
        'Nombre',
        'Email',
        'Numero_de_colaborador',
        'Rol',
        'Estado',
    ];

    protected $hidden = [
        'Numero_de_colaborador',
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