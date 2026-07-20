<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $table = 'Settings';
    protected $primaryKey = 'Id';
    protected $fillable = ['Clave', 'Valor'];

    public static function valor(string $clave, ?string $default = null): ?string
    {
        return static::where('Clave', $clave)->value('Valor') ?? $default;
    }
}
