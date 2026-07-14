<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Espacio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExperienciaReservaController extends Controller
{
    /**
     * Guarda la reservación directa de una Experiencia VIP (Sin mapa físico)
     */
    public function store(Request $request)
    {
        $request->validate([
            'serviciable_type' => 'required|string|in:App\Models\Balinesa,App\Models\CenaEspecial,App\Models\Experiencia',
            'serviciable_id' => 'required|integer',
            'fecha' => 'required|date_format:Y-m-d',
            'habitacion' => 'required|string',
            'numero_colaborador_vendedor' => 'required|string',
            'observaciones' => 'nullable|string',
        ]);

        $userId = auth()->id();
        $usuarioLogueadoId = (!empty($userId) && is_numeric($userId)) ? (int)$userId : 1;

        try {
            // 🌟 SOLUCIÓN: Usamos el ID comodín 61 en lugar de NULL para cumplir con el NOT NULL de SQL Server
            $reservaId = DB::table('Reservas')->insertGetId([
                'serviciable_type' => $request->serviciable_type,
                'serviciable_id' => (int)$request->serviciable_id,
                'id_espacio' => Espacio::where('Nombre', 'Sin Espacio Físico')->value('Id') ?? 1,
                'Dia' => $request->fecha,
                'Habitacion' => $request->habitacion,
                'Numero_de_colaborador_vendedor' => $request->numero_colaborador_vendedor,
                'Usuario_id' => $usuarioLogueadoId,
                'Estado' => 'Confirmado',
                'Observaciones' => $request->observaciones,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '¡Experiencia reservada con éxito!',
                'reserva_id' => $reservaId,
            ], 201);

        } catch (\Exception $e) {
            Log::error("Error reservando Experiencia VIP: " . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error interno al procesar la reserva. Intente de nuevo.'
            ], 500);
        }
    }
}