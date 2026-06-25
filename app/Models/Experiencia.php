<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experiencia extends Model
{
    protected $table = 'Experiencias';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
    'Nombre', 'Precio', 'Slug', 'Tipo', 'Lugar', 'Duracion', 'Horario', 'Numero_Personas', 'Is_Active', 'Id_Categoria', 'Ficha_Tecnica',
    'name', 'slug', 'price', 'tipo', 'lugar', 'duracion', 'horario', 'numero_personas', 'is_active', 'id_categoria', 'ficha_tecnica'
];

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