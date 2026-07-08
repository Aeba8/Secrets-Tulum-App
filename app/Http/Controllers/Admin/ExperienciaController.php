<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExperienciaController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'tipo' => 'nullable|string|max:100',
            'lugar' => 'nullable|string|max:150',
            'duracion' => 'nullable|string|max:100',
            'horario' => 'nullable|string|max:100',
            'numero_personas' => 'nullable|integer|min:1',
            'costo_operativo' => 'nullable|numeric|min:0',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activo' => 'boolean',
        ]);

        $imagenes = [];

        if ($request->hasFile('imagenes')) {
            $imagenes = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/experiencias'
            );
        }

        $slug = \Str::slug($validated['nombre']);

        Experiencia::create([
            'Nombre' => $validated['nombre'],
            'Descripcion' => $validated['descripcion'],
            'Precio' => $validated['precio'],
            'Tipo' => $validated['tipo'],
            'Lugar' => $validated['lugar'],
            'Duracion' => $validated['duracion'],
            'Horario' => $validated['horario'],
            'Numero_Personas' => $validated['numero_personas'] ?? 2,
            'Costo_Operativo' => $validated['costo_operativo'],
            'Slug' => $slug,
            'imagenes' => $imagenes,
            'Is_Active' => $request->boolean('activo', true),
            'Estado' => $request->boolean('activo', true) ? 'Activo' : 'Inactivo',
            'Id_Categoria' => 1,
        ]);

        return redirect()->route('admin.dashboard', '#experiencias')
            ->with('success', 'Experiencia creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $experiencia = Experiencia::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'tipo' => 'nullable|string|max:100',
            'lugar' => 'nullable|string|max:150',
            'duracion' => 'nullable|string|max:100',
            'horario' => 'nullable|string|max:100',
            'numero_personas' => 'nullable|integer|min:1',
            'costo_operativo' => 'nullable|numeric|min:0',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activo' => 'boolean',
        ]);

        $imagenes = $experiencia->imagenes ?? [];

        if ($request->hasFile('imagenes')) {
            $nuevas = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/experiencias'
            );
            $imagenes = array_merge($imagenes, $nuevas);
        }

        $experiencia->update([
            'Nombre' => $validated['nombre'],
            'Descripcion' => $validated['descripcion'],
            'Precio' => $validated['precio'],
            'Tipo' => $validated['tipo'],
            'Lugar' => $validated['lugar'],
            'Duracion' => $validated['duracion'],
            'Horario' => $validated['horario'],
            'Numero_Personas' => $validated['numero_personas'] ?? 2,
            'Costo_Operativo' => $validated['costo_operativo'],
            'imagenes' => $imagenes,
            'Is_Active' => $request->boolean('activo', true),
            'Estado' => $request->boolean('activo', true) ? 'Activo' : 'Inactivo',
        ]);

        return redirect()->route('admin.dashboard', '#experiencias')
            ->with('success', 'Experiencia actualizada correctamente.');
    }

    public function destroy($id)
    {
        $experiencia = Experiencia::findOrFail($id);

        $experiencia->update([
            'Is_Active' => false,
            'Estado' => 'Inactivo',
        ]);

        return redirect()->route('admin.dashboard', '#experiencias')
            ->with('success', 'Experiencia desactivada correctamente.');
    }

    public function activate($id)
    {
        $experiencia = Experiencia::findOrFail($id);

        $experiencia->update([
            'Is_Active' => true,
            'Estado' => 'Activo',
        ]);

        return redirect()->route('admin.dashboard', '#experiencias')
            ->with('success', 'Experiencia reactivada correctamente.');
    }
}
