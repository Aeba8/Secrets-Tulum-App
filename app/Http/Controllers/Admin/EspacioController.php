<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EspacioController extends Controller
{
    protected function reglasValidacion($id = null)
    {
        return [
            'nombre'     => 'required|string|max:50',
            'tipo'       => 'required|in:Balinesa,Mesa',
            'zona'       => 'required|string|max:50',
            'zona_nueva' => 'nullable|string|max:50',
            'capacidad'  => 'required|integer|min:1|max:100',
            'activo'     => 'nullable|in:0,1,on,off',
        ];
    }

    protected function mensajesValidacion(): array
    {
        return [
            'nombre.required'    => 'El nombre del espacio es obligatorio.',
            'nombre.max'         => 'El nombre no debe exceder 50 caracteres.',
            'tipo.required'      => 'El tipo de espacio es obligatorio.',
            'tipo.in'            => 'El tipo debe ser Balinesa o Mesa.',
            'zona.required'      => 'La zona es obligatoria.',
            'zona.max'           => 'La zona no debe exceder 50 caracteres.',
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer'  => 'La capacidad debe ser un número entero.',
            'capacidad.min'      => 'La capacidad mínima es 1.',
            'capacidad.max'      => 'La capacidad máxima es 100.',
        ];
    }

    protected function validarYFallar(Request $request, $id = null): array
    {
        $validator = Validator::make(
            $request->all(),
            $this->reglasValidacion($id),
            $this->mensajesValidacion()
        );

        if ($validator->fails()) {
            $hash = $request->input('tipo', 'Balinesa') === 'Balinesa' ? '#espacios-balinesas' : '#espacios-mesas';
            $redirect = redirect(route('admin.dashboard') . $hash)
                ->withErrors($validator)
                ->withInput();
            if ($id) {
                $redirect->with('edit_id', $id);
            }
            throw new \Illuminate\Validation\ValidationException($validator, $redirect);
        }

        return $validator->validated();
    }

    public function store(Request $request)
    {
        $validated = $this->validarYFallar($request);

        $zona = $validated['zona'];
        if ($zona === '__new__' && !empty($validated['zona_nueva'])) {
            $zona = strip_tags($validated['zona_nueva']);
        }

        Espacio::create([
            'Nombre'    => strip_tags($validated['nombre']),
            'Tipo'      => $validated['tipo'],
            'Zona'      => strip_tags($zona),
            'Capacidad' => (int) $validated['capacidad'],
            'Estado'    => 'DISPONIBLE',
            'Is_Active' => $request->boolean('activo'),
        ]);

        $hash = $validated['tipo'] === 'Balinesa' ? '#espacios-balinesas' : '#espacios-mesas';
        return redirect(route('admin.dashboard') . $hash)
            ->with('success', 'Espacio creado correctamente.');
    }

    public function update(Request $request, $id)
    {
        $espacio = Espacio::findOrFail($id);
        $validated = $this->validarYFallar($request);

        $zona = $validated['zona'];
        if ($zona === '__new__' && !empty($validated['zona_nueva'])) {
            $zona = strip_tags($validated['zona_nueva']);
        }

        $espacio->update([
            'Nombre'    => strip_tags($validated['nombre']),
            'Tipo'      => $validated['tipo'],
            'Zona'      => strip_tags($zona),
            'Capacidad' => (int) $validated['capacidad'],
            'Is_Active' => $request->boolean('activo'),
        ]);

        $hash = $validated['tipo'] === 'Balinesa' ? '#espacios-balinesas' : '#espacios-mesas';
        return redirect(route('admin.dashboard') . $hash)
            ->with('success', 'Espacio actualizado correctamente.');
    }

    public function destroy($id)
    {
        $espacio = Espacio::findOrFail($id);
        $espacio->update(['Is_Active' => 0]);
        $hash = $espacio->Tipo === 'Balinesa' ? '#espacios-balinesas' : '#espacios-mesas';
        return redirect(route('admin.dashboard') . $hash)
            ->with('success', 'Espacio desactivado correctamente.');
    }

    public function activate($id)
    {
        $espacio = Espacio::findOrFail($id);
        $espacio->update(['Is_Active' => 1]);
        $hash = $espacio->Tipo === 'Balinesa' ? '#espacios-balinesas' : '#espacios-mesas';
        return redirect(route('admin.dashboard') . $hash)
            ->with('success', 'Espacio activado correctamente.');
    }
}
