<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CenaEspecialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->Id,
            'id_categoria'    => (int) $this->id_categoria,
            'nombre'          => $this->Nombre,
            'slug'            => $this->slug,
            'precio'          => (float) $this->Precio,
            'restaurant'      => $this->restaurant,
            'numero_personas' => (int) $this->numero_personas,
            'is_active'       => ($this->Estado ?? 'Inactivo') === 'Activo',
            'ficha_tecnica'   => $this->ficha_tecnica,
            'imagenes'        => $this->imagenes,
            
            // 🌟 Agregamos el desglose del menú al JSON de salida:
            'entrada'         => $this->Entrada,
            'crema'           => $this->Crema,
            'plato_fuerte'    => $this->Plato_fuerte,
            'postre'          => $this->Postre,
        ];
    }
}