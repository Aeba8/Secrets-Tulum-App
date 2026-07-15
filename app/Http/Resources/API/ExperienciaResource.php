<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienciaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'              => $this->Id,
            'id_categoria'    => (int) $this->id_categoria,
            'nombre'          => $this->Nombre,
            'slug'            => $this->slug,
            'precio'          => (float) $this->Precio,
            'tipo'            => $this->Tipo,
            'lugar'           => $this->Lugar,
            'duracion'        => $this->Duracion,
            'horario'         => $this->Horario,
            'numero_personas' => (int) $this->numero_personas,
            'is_active'       => ($this->Estado ?? 'Inactivo') === 'Activo',
            'ficha_tecnica'   => $this->ficha_tecnica,
            'descripcion'     => $this->Descripcion,
            'productos'       => $this->Productos,
            'imagenes'        => $this->imagenes,
            'costo_operativo' => (float) $this->Costo_Operativo,
            'estado'          => $this->Estado,
        ];
    }
}
