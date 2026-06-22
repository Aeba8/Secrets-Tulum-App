<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'Reservas';
    protected $primaryKey = 'Id';
    
    // Indicamos que use los nombres exactos de las columnas de auditoría de tu script
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';

    // Relación Polimórfica Dinámica
    public function serviciable()
    {
        return $this->morphTo('serviciable', 'serviciable_type', 'serviciable_id');
    }

    // Relación con el Espacio asignado (Mesa/Cama)
    public function espacio()
    {
        return $this->belongsTo(Espacio::class, 'id_espacio', 'Id');
    }

    // Relación con el Usuario (Capitán) que validó la reserva
    public function usuario()
    {
        return $this->belongsTo(Usuario::class, 'Usuario_id', 'Id');
    }
}