<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Balinesa extends Model
{
    protected $table = 'Balinesas';
    protected $primaryKey = 'Id';
    public $timestamps = false; // Mantenemos tu configuración original

    // Campos habilitados para la asignación masiva del catálogo
    protected $fillable = [
    'Nombre', 'Precio', 'Slug', 'Capacidad_Maxima', 'Is_Active', 'Id_Categoria', 'Ficha_Tecnica',
    'name', 'slug', 'price', 'capacidad_maxima', 'is_active', 'id_categoria', 'ficha_tecnica',
    'imagenes',
];

    // Castea automáticamente el JSON de SQL Server a un array interactivo de PHP
    protected function casts(): array
    {
        return [
            'Ficha_Tecnica' => 'array',
            'Capacidad_Maxima' => 'integer',
            'Is_Active' => 'boolean',
            'imagenes' => 'array',
        ];
    }

    // Tu relación inversa del polimorfismo intacta para que no se rompan las reservas
    public function reservas()
    {
        return $this->morphMany(Reserva::class, 'serviciable', 'serviciable_type', 'serviciable_id');
    }
}