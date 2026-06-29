<?php

namespace App\Http\Controllers;

use App\Models\Balinesa;
use App\Models\Categoria;
use App\Models\CenaEspecial;
use App\Models\Experiencia;
use Illuminate\Http\Request;

class HotelWebController extends Controller
{
    public function mostrarCatalogo(Request $request)
    {
        // Trae todas las categorías registradas en la DB (ordenadas por su ID)
        $categorias = Categoria::orderBy('Id', 'asc')->get();

        // Carga el blade y le pasa la colección de categorías
        return view('ipad.catalogo', compact('categorias'));
    }

    public function detalleBalinesa(Request $request, $slug)
    {
        // Buscamos la balinesa por su slug en la base de datos
        $balinesa = Balinesa::where('slug', $slug)->firstOrFail();

        // Retornamos la vista dedicada pasándole el modelo
        return view('ipad.detalle-balinesa', compact('balinesa'));
    }
}
