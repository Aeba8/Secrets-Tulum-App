<?php

namespace App\Http\Resources\API;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExperienciaResource extends JsonResource
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
            'tipo'            => $this->Tipo,
            'lugar'           => $this->Lugar,
            'duracion'        => $this->Duracion,
            'horario'         => $this->Horario,
            'numero_personas' => (int) $this->numero_personas,
            'is_active'       => (bool) $this->is_active,
            'ficha_tecnica'   => $this->ficha_tecnica,
            'descripcion'     => $this->Descripcion,
            'productos'       => $this->Productos, // Si activaste el cast a array, se enviará como objeto/lista limpia
            'imagenes'        => $this->imagenes,  // Si activaste el cast a array, se enviará como lista de URLs
            'costo_operativo' => (float) $this->Costo_Operativo,
            'estado'          => $this->Estado,
        ];
    }
}
