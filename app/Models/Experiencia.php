<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table = 'experiencias';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'nombre', 'descripcion', 'precio', 'costo_operativo', 'slug', 'tipo', 'lugar',
        'duracion', 'horario', 'numero_personas', 'id_categoria', 'imagenes',
        'productos', 'ficha_tecnica', 'estado',
    ];

    protected function casts(): array
    {
        return [
            'imagenes' => 'array',
            'costo_operativo' => 'decimal:2',
            'numero_personas' => 'integer',
        ];
    }

    public function reservas()
    {
        return $this->morphMany(Reserva::class, 'serviciable', 'serviciable_type', 'serviciable_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'Id');
    }
}
