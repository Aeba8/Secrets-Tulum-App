<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CenaEspecial;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CenaEspecialController extends Controller
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
            'restaurant' => 'nullable|string|max:150',
            'precio' => 'required|numeric|min:0',
            'numero_personas' => 'nullable|integer|min:1',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activo' => 'boolean',
        ]);

        $imagenes = [];

        if ($request->hasFile('imagenes')) {
            $imagenes = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/cenas'
            );
        }

        $slug = \Str::slug($validated['nombre']);

        CenaEspecial::create([
            'Nombre' => $validated['nombre'],
            'Restaurant' => strip_tags($validated['restaurant'] ?? ''),
            'Precio' => $validated['precio'],
            'Numero_Personas' => $validated['numero_personas'] ?? 2,
            'Slug' => $slug,
            'imagenes' => $imagenes,
            'Is_Active' => $request->boolean('activo', true),
            'Id_Categoria' => 1,
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $cena = CenaEspecial::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:150',
            'restaurant' => 'nullable|string|max:150',
            'precio' => 'required|numeric|min:0',
            'numero_personas' => 'nullable|integer|min:1',
            'imagenes.*' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'activo' => 'boolean',
        ]);

        $imagenes = $cena->imagenes ?? [];

        if ($request->hasFile('imagenes')) {
            $nuevas = $this->cloudinary->uploadImages(
                $request->file('imagenes'),
                'secretspad/paquetes/cenas'
            );
            $imagenes = array_merge($imagenes, $nuevas);
        }

        $cena->update([
            'Nombre' => $validated['nombre'],
            'Restaurant' => strip_tags($validated['restaurant'] ?? ''),
            'Precio' => $validated['precio'],
            'Numero_Personas' => $validated['numero_personas'] ?? 2,
            'imagenes' => $imagenes,
            'Is_Active' => $request->boolean('activo', true),
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena actualizada correctamente.');
    }

    public function destroy($id)
    {
        $cena = CenaEspecial::findOrFail($id);

        $cena->update([
            'Is_Active' => false,
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena desactivada correctamente.');
    }

    public function activate($id)
    {
        $cena = CenaEspecial::findOrFail($id);

        $cena->update([
            'Is_Active' => true,
        ]);

        return redirect(route('admin.dashboard') . '#cenas')
            ->with('success', 'Cena reactivada correctamente.');
    }
}
