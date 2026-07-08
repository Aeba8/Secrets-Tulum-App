<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\CenaEspecial;
use App\Models\Experiencia;
use App\Models\Balinesa;
use App\Http\Resources\API\CenaEspecialResource;
use App\Http\Resources\API\ExperienciaResource;
use App\Http\Resources\API\BalinesaResource;
use Illuminate\Http\JsonResponse;

class CatalogController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            // 1. Consultar colecciones filtradas desde SQL Server
            $cenas = CenaEspecial::where('Estado', 'Activo')->get();
            $experiencias = Experiencia::where('Estado', 'Activo')->get();
            $balinesas = Balinesa::where('Estado', 'Activo')->get();

            // 2. Retornar la respuesta pasando los datos por los transformadores limpios
            return response()->json([
                'success' => true,
                'message' => 'Catálogo del hotel recuperado con éxito.',
                'data'    => [
                    'cenas_especiales' => CenaEspecialResource::collection($cenas),
                    'experiencias'     => ExperienciaResource::collection($experiencias),
                    'balinesas'        => BalinesaResource::collection($balinesas)
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hubo un error al obtener el catálogo.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
}