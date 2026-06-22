<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'Categorias';
    protected $primaryKey = 'Id';
    public $timestamps = false; // Nuestro script no tiene created_at aquí
}
