<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    protected $table = 'Espacios';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre', 'Tipo', 'Zona', 'Capacidad', 'Estado', 'Is_Active',
    ];
}