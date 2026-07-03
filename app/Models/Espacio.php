<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Espacio extends Model
{
    // Forzamos el nombre exacto con la mayúscula de SQL Server
    protected $table = 'Espacios';
    
    // Forzamos la clave primaria con mayúscula
    protected $primaryKey = 'Id';
    
    // Si tu tabla no tiene las columnas created_at y updated_at, desactívalas para que no marque error:
    public $timestamps = false; 
}