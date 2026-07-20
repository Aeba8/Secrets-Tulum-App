<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balinesa;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class BalinesaController extends Controller
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
            'capacidad' => 'nullable|integer|min:1|max:500',
            'horario' => 'nullable|string|max:100',
            'botella_incluida' => 'nullable|string|max:255',
            'alimentos_bebidas' => 'nullable|string|max:1000',
            'costo_operativo' => 'nullable|numeric|min:0|max:999999.99',
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
                'secretspad/paquetes/balinesas'
            );
        }

        $slug = Str::slug($validated['nombre']);

        $ultimoOrden = Balinesa::max('Orden') ?? 0;

        Balinesa::create([
            'Nombre' => $validated['nombre'],
            'Descripcion' => strip_tags($validated['descripcion'] ?? ''),
            'Precio' => $validated['precio'],
            'slug' => $slug,
            'capacidad_maxima' => $validated['capacidad'] ?? 2,
            'Dias' => strip_tags($validated['horario'] ?? ''),
            'ficha_tecnica' => strip_tags($validated['botella_incluida'] ?? ''),
            'Productos' => strip_tags($validated['alimentos_bebidas'] ?? ''),
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'imagenes' => $imagenes,
            'Estado' => $request->boolean('activo') ? 'Activo' : 'Inactivo',
            'id_categoria' => $validated['categoria_id'] ?? 1,
            'Orden' => $ultimoOrden + 1,
        ]);

        return redirect(route('admin.dashboard') . '#balinesas')
            ->with('success', 'Balinesa creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $balinesa = Balinesa::findOrFail($id);

        $validated = $request->validate($this->reglasValidacion());

        $imagenes = $balinesa->imagenes ?? [];

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
                'secretspad/paquetes/balinesas'
            );
            $imagenes = array_merge($imagenes, $nuevas);
        }

        $balinesa->update([
            'Nombre' => $validated['nombre'],
            'Descripcion' => strip_tags($validated['descripcion'] ?? ''),
            'Precio' => $validated['precio'],
            'capacidad_maxima' => $validated['capacidad'] ?? 2,
            'Dias' => strip_tags($validated['horario'] ?? ''),
            'ficha_tecnica' => strip_tags($validated['botella_incluida'] ?? ''),
            'Productos' => strip_tags($validated['alimentos_bebidas'] ?? ''),
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'imagenes' => $imagenes,
            'Estado' => $request->boolean('activo') ? 'Activo' : 'Inactivo',
        ]);

        return redirect(route('admin.dashboard') . '#balinesas')
            ->with('success', 'Balinesa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $balinesa = Balinesa::findOrFail($id);

        $balinesa->update([
            'Estado' => 'Inactivo',
        ]);

        return redirect(route('admin.dashboard') . '#balinesas')
            ->with('success', 'Balinesa desactivada correctamente.');
    }

    public function activate($id)
    {
        $balinesa = Balinesa::findOrFail($id);

        $balinesa->update([
            'Estado' => 'Activo',
        ]);

        return redirect(route('admin.dashboard') . '#balinesas')
            ->with('success', 'Balinesa reactivada correctamente.');
    }
}
