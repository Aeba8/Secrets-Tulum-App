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

        // Procesamos la ficha técnica antes de armar el array
        $parts = explode('|', $this->ficha_tecnica ?? '');

        return [
            'id'               => $this->Id,
            'id_categoria'     => (int) $this->id_categoria,
            'nombre'           => $this->Nombre,
            'slug'             => $this->slug,
            'precio'           => (float) $this->Precio,
            'capacidad_maxima' => (int) $this->capacidad_maxima,
            'is_active'        => (bool) $this->is_active,
            'ficha_tecnica'    => $this->ficha_tecnica,
            'descripcion'      => $this->Descripcion,
            'productos'         => $this->Productos,

            // Enviamos los campos ya divididos de forma limpia
            'horario_disponible' => isset($parts[0]) ? trim($parts[0]) : null,
            'botella_incluida'   => isset($parts[1]) ? trim($parts[1]) : null,
        ];
    }
}
