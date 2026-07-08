<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table = 'Experiencias';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre', 'Descripcion', 'Precio', 'Costo_Operativo', 'Slug', 'Tipo', 'Lugar',
        'Duracion', 'Horario', 'Numero_Personas', 'id_categoria', 'imagenes',
        'Productos', 'ficha_tecnica', 'Estado',
    ];

    protected function casts(): array
    {
        return [
            'imagenes' => 'array',
            'Costo_Operativo' => 'decimal:2',
            'Numero_Personas' => 'integer',
        ];
    }

    public function reservas()
    {
        return $this->morphMany(Reserva::class, 'serviciable', 'serviciable_type', 'serviciable_id');
    }
}
