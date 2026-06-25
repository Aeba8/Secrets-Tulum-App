<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CenaEspecial extends Model
{
    protected $table = 'Cenas_Especiales';
    protected $primaryKey = 'Id';
    public $timestamps = false; // Se mantiene false como lo tienes originalmente

    // Campos habilitados para la tablet y control
    protected $fillable = [
    'Nombre', 'Precio', 'Slug', 'Restaurant', 'Numero_Personas', 'Is_Active', 'Id_Categoria', 'Ficha_Tecnica',
    'name', 'slug', 'price', 'restaurant', 'numero_personas', 'is_active', 'id_categoria', 'ficha_tecnica'
];

    // Transforma el JSON de SQL Server automáticamente en array de PHP
    protected function casts(): array
    {
        return [
            'Ficha_Tecnica' => 'array',
            'Numero_Personas' => 'integer',
            'Is_Active' => 'boolean',
        ];
    }

    // Tu relación inversa del polimorfismo intacta
    public function reservas()
    {
        return $this->morphMany(Reserva::class, 'serviciable', 'serviciable_type', 'serviciable_id');
    }
}
