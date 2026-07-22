<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CenaEspecial;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CenaEspecialController extends Controller
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
            'restaurant' => 'required|string|max:150',
            'entrada' => 'nullable|string|max:1000',
            'crema' => 'nullable|string|max:1000',
            'plato_fuerte' => 'nullable|string|max:1000',
            'postre' => 'nullable|string|max:1000',
            'precio' => 'required|numeric|min:0|max:999999.99',
            'costo_operativo' => 'nullable|numeric|min:0|max:999999.99',
            'numero_personas' => 'nullable|integer|min:1|max:500',
            'ficha_tecnica' => 'nullable|string|max:1000',
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
                'secretspad/paquetes/cenas'
            );
        }

        $slug = Str::slug($validated['nombre']);

        $ultimoOrden = CenaEspecial::max('Orden') ?? 0;

        CenaEspecial::create([
            'Nombre' => $validated['nombre'],
            'restaurant' => $validated['restaurant'],
            'Entrada' => strip_tags($validated['entrada'] ?? ''),
            'Crema' => strip_tags($validated['crema'] ?? ''),
            'Plato_fuerte' => strip_tags($validated['plato_fuerte'] ?? ''),
            'Postre' => strip_tags($validated['postre'] ?? ''),
            'Precio' => $validated['precio'],
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'numero_personas' => $validated['numero_personas'] ?? 2,
            'ficha_tecnica' => strip_tags($validated['ficha_tecnica'] ?? ''),
            'slug' => $slug,
            'imagenes' => $imagenes,
            'Estado' => $request->boolean('activo') ? 'Activo' : 'Inactivo',
            'id_categoria' => $validated['categoria_id'] ?? 1,
            'Orden' => $ultimoOrden + 1,
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $cena = CenaEspecial::findOrFail($id);

        $validated = $request->validate($this->reglasValidacion());

        $imagenes = $cena->imagenes ?? [];

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
                'secretspad/paquetes/cenas'
            );
            $imagenes = array_merge($imagenes, $nuevas);
        }

        $cena->update([
            'Nombre' => $validated['nombre'],
            'restaurant' => $validated['restaurant'],
            'Entrada' => strip_tags($validated['entrada'] ?? ''),
            'Crema' => strip_tags($validated['crema'] ?? ''),
            'Plato_fuerte' => strip_tags($validated['plato_fuerte'] ?? ''),
            'Postre' => strip_tags($validated['postre'] ?? ''),
            'Precio' => $validated['precio'],
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'numero_personas' => $validated['numero_personas'] ?? 2,
            'ficha_tecnica' => strip_tags($validated['ficha_tecnica'] ?? ''),
            'imagenes' => $imagenes,
            'Estado' => $request->boolean('activo') ? 'Activo' : 'Inactivo',
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena actualizada correctamente.');
    }

    public function destroy($id)
    {
        $cena = CenaEspecial::findOrFail($id);

        $cena->update([
            'Estado' => 'Inactivo',
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena desactivada correctamente.');
    }

    public function activate($id)
    {
        $cena = CenaEspecial::findOrFail($id);

        $cena->update([
            'Estado' => 'Activo',
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena reactivada correctamente.');
    }
}
