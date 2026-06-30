<?php

namespace App\Http\Controllers;

use App\Models\Balinesa;
use App\Models\Categoria;
use App\Models\CenaEspecial;
use Illuminate\Http\Request;
use App\Models\Experiencia;

class HotelWebController extends Controller
{
    public function mostrarCatalogo(Request $request)
    {
        // Trae todas las categorías registradas en la DB (ordenadas por su ID)
        $categorias = Categoria::orderBy('Id', 'asc')->get();

        // Carga el blade y le pasa la colección de categorías
        return view('ipad.catalogo', compact('categorias'));
    }

    public function detalleExperiencia($slug)
    {
        // Buscamos por el slug en minúsculas o mayúsculas según manejes tu BD
        $experiencia = Experiencia::where('slug', $slug)
            ->orWhere('Slug', $slug)
            ->firstOrFail();

        return view('ipad.detalle-experiencia', compact('experiencia'));
    }

    public function detalleBalinesa(Request $request, $slug)
    {
        // Buscamos la balinesa por su slug en la base de datos
        $balinesa = Balinesa::where('slug', $slug)->firstOrFail();

        // Retornamos la vista dedicada pasándole el modelo
        return view('ipad.detalle-balinesa', compact('balinesa'));
    }

    public function detalleCena($slug)
    {
        // Buscamos la cena en la base de datos usando el slug de la URL
        $cena = CenaEspecial::where('slug', $slug)->firstOrFail();

        // Retornamos la vista pasándole el objeto obtenido
        return view('ipad.detalle-cena', compact('cena'));
    }
}
