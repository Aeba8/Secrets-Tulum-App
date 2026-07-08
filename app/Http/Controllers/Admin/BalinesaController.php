<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Balinesa;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BalinesaController extends Controller
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
            'capacidad' => 'nullable|integer|min:1',
            'horario' => 'nullable|string|max:100',
            'botella_incluida' => 'nullable|string|max:255',
            'alimentos_bebidas' => 'nullable|string',
            'costo_operativo' => 'nullable|numeric|min:0',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activo' => 'boolean',
        ]);

        $imagenes = [];

        if ($request->hasFile('imagenes')) {
            $imagenes = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/balinesas'
            );
        }

        $slug = \Str::slug($validated['nombre']);

        Balinesa::create([
            'Nombre' => $validated['nombre'],
            'Descripcion' => $validated['descripcion'],
            'Precio' => $validated['precio'],
            'Slug' => $slug,
            'capacidad_maxima' => $validated['capacidad'] ?? 2,
            'Dias' => $validated['horario'],
            'ficha_tecnica' => $validated['botella_incluida'],
            'Productos' => $validated['alimentos_bebidas'],
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'imagenes' => $imagenes,
            'Is_Active' => $request->boolean('activo', true),
            'Estado' => $request->boolean('activo', true) ? 'Activo' : 'Inactivo',
            'Id_Categoria' => 1,
        ]);

        return redirect()->route('admin.dashboard', '#balinesas')
            ->with('success', 'Balinesa creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $balinesa = Balinesa::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric|min:0',
            'capacidad' => 'nullable|integer|min:1',
            'horario' => 'nullable|string|max:100',
            'botella_incluida' => 'nullable|string|max:255',
            'alimentos_bebidas' => 'nullable|string',
            'costo_operativo' => 'nullable|numeric|min:0',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activo' => 'boolean',
        ]);

        $imagenes = $balinesa->imagenes ?? [];

        if ($request->hasFile('imagenes')) {
            $nuevas = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/balinesas'
            );
            $imagenes = array_merge($imagenes, $nuevas);
        }

        $balinesa->update([
            'Nombre' => $validated['nombre'],
            'Descripcion' => $validated['descripcion'],
            'Precio' => $validated['precio'],
            'capacidad_maxima' => $validated['capacidad'] ?? 2,
            'Dias' => $validated['horario'],
            'ficha_tecnica' => $validated['botella_incluida'],
            'Productos' => $validated['alimentos_bebidas'],
            'Costo_Operativo' => $validated['costo_operativo'] ?? 0,
            'imagenes' => $imagenes,
            'Is_Active' => $request->boolean('activo', true),
            'Estado' => $request->boolean('activo', true) ? 'Activo' : 'Inactivo',
        ]);

        return redirect()->route('admin.dashboard', '#balinesas')
            ->with('success', 'Balinesa actualizada correctamente.');
    }

    public function destroy($id)
    {
        $balinesa = Balinesa::findOrFail($id);

        $balinesa->update([
            'Is_Active' => false,
            'Estado' => 'Inactivo',
        ]);

        return redirect()->route('admin.dashboard', '#balinesas')
            ->with('success', 'Balinesa desactivada correctamente.');
    }

    public function activate($id)
    {
        $balinesa = Balinesa::findOrFail($id);

        $balinesa->update([
            'Is_Active' => true,
            'Estado' => 'Activo',
        ]);

        return redirect()->route('admin.dashboard', '#balinesas')
            ->with('success', 'Balinesa reactivada correctamente.');
    }
}
