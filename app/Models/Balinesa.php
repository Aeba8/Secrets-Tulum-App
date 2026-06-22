<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balinesa extends Model
{
    protected $table = 'Balinesas';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    // Relación inversa del polimorfismo
    public function reservas()
    {
        return $this->morphMany(Reserva::class, 'serviciable', 'serviciable_type', 'serviciable_id');
    }
}
