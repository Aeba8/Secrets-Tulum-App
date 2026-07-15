<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienciaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->id,
            'id_categoria'    => (int) $this->id_categoria,
            'nombre'          => $this->nombre,
            'slug'            => $this->slug,
            'precio'          => (float) $this->precio,
            'tipo'            => $this->tipo,
            'lugar'           => $this->lugar,
            'duracion'        => $this->duracion,
            'horario'         => $this->horario,
            'numero_personas' => (int) $this->numero_personas,
            'is_active'       => ($this->estado ?? 'Inactivo') === 'Activo',
            'ficha_tecnica'   => $this->ficha_tecnica,
            'descripcion'     => $this->descripcion,
            'productos'       => $this->productos,
            'imagenes'        => $this->imagenes,
            'costo_operativo' => (float) $this->costo_operativo,
            'estado'          => $this->estado,
        ];
    }
}
