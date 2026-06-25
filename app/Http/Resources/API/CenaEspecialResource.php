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
            'is_active'       => (bool) $this->is_active,
            'ficha_tecnica'   => $this->ficha_tecnica, // Laravel ya lo decodifica a array/objeto gracias al cast
        ];
}
}

