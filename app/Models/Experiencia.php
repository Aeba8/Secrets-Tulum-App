<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table = 'Experiencias';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    // Relación inversa del polimorfismo
    public function reservas()
    {
        return $this->morphMany(Reserva::class, 'serviciable', 'serviciable_type', 'serviciable_id');
    }
}
