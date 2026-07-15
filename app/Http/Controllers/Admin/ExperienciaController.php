<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Experiencia;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ExperienciaController extends Controller
{
    protected CloudinaryService $cloudinary;

    public function __construct(CloudinaryService $cloudinary)
    {
        $this->cloudinary = $cloudinary;
    }

    protected function reglasValidacion(): array
    {
        return [
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string|max:1000',
            'precio' => 'required|numeric|min:0|max:999999.99',
            'tipo' => 'nullable|string|max:100',
            'lugar' => 'nullable|string|max:150',
            'duracion' => 'nullable|string|max:100',
            'horario' => 'nullable|string|max:100',
            'numero_personas' => 'nullable|integer|min:1|max:500',
            'costo_operativo' => 'nullable|numeric|min:0|max:999999.99',
            'productos' => 'nullable|string|max:1000',
            'botella' => 'nullable|string|max:255',
            'servicio_extra' => 'nullable|string|max:255',
            'categoria_id' => 'nullable|integer|exists:Categorias,Id',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activo' => 'boolean',
        ];
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->reglasValidacion());

        $imagenes = [];

        if ($request->hasFile('imagenes')) {
            $imagenes = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/experiencias'
            );
        }

        $slug = Str::slug($validated['nombre']);

        $botella = strip_tags($validated['botella'] ?? '');
        $servicio_extra = strip_tags($validated['servicio_extra'] ?? '');
        $ficha_tecnica = $botella . ($servicio_extra ? '|' . $servicio_extra : '');

        Experiencia::create([
            'Nombre' => $validated['nombre'],
            'Descripcion' => strip_tags($validated['descripcion'] ?? ''),
            'Precio' => $validated['precio'],
            'Tipo' => strip_tags($validated['tipo'] ?? ''),
            'Lugar' => strip_tags($validated['lugar'] ?? ''),
            'Duracion' => strip_tags($validated['duracion'] ?? ''),
            'Horario' => strip_tags($validated['horario'] ?? ''),
            'numero_personas' => $validated['numero_personas'] ?? 2,
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'Productos' => strip_tags($validated['productos'] ?? ''),
            'ficha_tecnica' => $ficha_tecnica,
            'slug' => $slug,
            'imagenes' => $imagenes,
            'Estado' => $request->boolean('activo') ? 'Activo' : 'Inactivo',
            'id_categoria' => $validated['categoria_id'] ?? 1,
        ]);

        return redirect(route('admin.dashboard') . '#experiencias')
            ->with('success', 'Experiencia creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $experiencia = Experiencia::findOrFail($id);

        $validated = $request->validate($this->reglasValidacion());

        $imagenes = $experiencia->imagenes ?? [];

        $eliminar = $request->input('imagenes_eliminar', '');
        if (!empty($eliminar)) {
            $urlsEliminar = array_filter(explode(',', $eliminar));
            $urlsEliminar = array_intersect($urlsEliminar, $imagenes);
            if (!empty($urlsEliminar)) {
                $this->cloudinary->deleteImagesByUrls($urlsEliminar);
                $imagenes = array_values(array_diff($imagenes, $urlsEliminar));
            }
        }

        if ($request->hasFile('imagenes')) {
            $nuevas = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/experiencias'
            );
            $imagenes = array_merge($imagenes, $nuevas);
        }

        $botella = strip_tags($validated['botella'] ?? '');
        $servicio_extra = strip_tags($validated['servicio_extra'] ?? '');
        $ficha_tecnica = $botella . ($servicio_extra ? '|' . $servicio_extra : '');

        $experiencia->update([
            'Nombre' => $validated['nombre'],
            'Descripcion' => strip_tags($validated['descripcion'] ?? ''),
            'Precio' => $validated['precio'],
            'Tipo' => strip_tags($validated['tipo'] ?? ''),
            'Lugar' => strip_tags($validated['lugar'] ?? ''),
            'Duracion' => strip_tags($validated['duracion'] ?? ''),
            'Horario' => strip_tags($validated['horario'] ?? ''),
            'numero_personas' => $validated['numero_personas'] ?? 2,
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'Productos' => strip_tags($validated['productos'] ?? ''),
            'ficha_tecnica' => $ficha_tecnica,
            'imagenes' => $imagenes,
            'Estado' => $request->boolean('activo') ? 'Activo' : 'Inactivo',
        ]);

        return redirect(route('admin.dashboard') . '#experiencias')
            ->with('success', 'Experiencia actualizada correctamente.');
    }

    public function destroy($id)
    {
        $experiencia = Experiencia::findOrFail($id);

        $experiencia->update([
            'Estado' => 'Inactivo',
        ]);

        return redirect(route('admin.dashboard') . '#experiencias')
            ->with('success', 'Experiencia desactivada correctamente.');
    }

    public function activate($id)
    {
        $experiencia = Experiencia::findOrFail($id);

        $experiencia->update([
            'Estado' => 'Activo',
        ]);

        return redirect(route('admin.dashboard') . '#experiencias')
            ->with('success', 'Experiencia reactivada correctamente.');
    }
}
