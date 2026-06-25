<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'Categorias';
    protected $primaryKey = 'Id';
    public $timestamps = false; // Nuestro script no tiene created_at aquí

    // Permitimos la inserción segura de los campos requeridos por SQL Server
    protected $fillable = [
        'Nombre',
        'Slug'
    ];
}
