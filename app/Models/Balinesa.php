<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balinesa extends Model
{
    protected $table = 'Balinesas';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'Nombre', 'Precio', 'Capacidad_Maxima', 'Is_Active', 'Id_Categoria', 'Ficha_Tecnica',
        'name', 'slug', 'price', 'capacidad_maxima', 'is_active', 'id_categoria', 'ficha_tecnica',
        'imagenes', 'Productos', 'Costo_Operativo', 'Dias', 'Descripcion', 'Estado',
    ];

    protected function casts(): array
    {
        return [
            'capacidad_maxima' => 'integer',
            'is_active' => 'boolean',
            'imagenes' => 'array',
            'Precio' => 'float',
            'Costo_Operativo' => 'float',
        ];
    }

    public function reservas()
    {
        return $this->morphMany(Reserva::class, 'serviciable', 'serviciable_type', 'serviciable_id');
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'Id_Categoria', 'Id');
    }
}
