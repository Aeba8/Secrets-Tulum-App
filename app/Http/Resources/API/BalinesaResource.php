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
        // Procesamos la ficha técnica por si viene con el formato "Horario | Botella"
        $parts = explode('|', $this->ficha_tecnica ?? '');

        // Si el string contenía un '|', la botella será la segunda parte; 
        // de lo contrario, tomamos el campo ficha_tecnica completo de la BD.
        $botellaIncluida = isset($parts[1]) ? trim($parts[1]) : trim($this->ficha_tecnica ?? '');

        // Para los días (horario), priorizamos la columna 'Dias'/'dias' de la BD. 
        // Si viene vacía, usamos la primera parte de la ficha técnica como respaldo.
        $diasHorario = !empty($this->Dias) ? $this->Dias : (!empty($this->dias) ? $this->dias : (isset($parts[0]) ? trim($parts[0]) : null));

        return [
            'id'               => $this->Id,
            'id_categoria'     => (int) $this->id_categoria,
            'nombre'           => $this->Nombre,
            'slug'             => $this->slug,
            'precio'           => (float) $this->Precio,
            'capacidad_maxima' => (int) $this->capacidad_maxima,
            'is_active'        => (bool) $this->is_active,
            'descripcion'      => $this->Descripcion,
            'productos'        => $this->Productos,

            // 🌟 Atributos corregidos según tus indicaciones:
            'ficha_tecnica'    => $botellaIncluida, // Muestra únicamente la Botella incluida
            'dias'             => $diasHorario,     // Muestra el Horario o Disponibilidad
        ];
    }
}