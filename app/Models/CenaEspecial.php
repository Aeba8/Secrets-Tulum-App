<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CenaEspecial extends Model
{
    protected $table = 'Cenas_Especiales';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre', 'Precio', 'Costo_Operativo', 'Slug', 'id_categoria', 'imagenes',
        'restaurant', 'numero_personas',
        'Entrada', 'Crema', 'Plato_fuerte', 'Postre', 'ficha_tecnica', 'Estado',
    ];

    protected function casts(): array
    {
        return [
            'imagenes' => 'array',
            'Costo_Operativo' => 'decimal:2',
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
