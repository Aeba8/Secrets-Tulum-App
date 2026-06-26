<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
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
}