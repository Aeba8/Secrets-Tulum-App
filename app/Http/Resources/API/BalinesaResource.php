<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BalinesaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->Id,
            'id_categoria'     => (int) $this->id_categoria,
            'nombre'           => $this->Nombre,
            'slug'             => $this->slug,
            'precio'           => (float) $this->Precio,
            'capacidad_maxima' => (int) $this->capacidad_maxima,
            'is_active'        => (bool) $this->is_active,
            'ficha_tecnica'    => $this->ficha_tecnica,
        ];
    }
}
