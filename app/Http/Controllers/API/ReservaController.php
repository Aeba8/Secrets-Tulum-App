<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Balinesa;
use App\Models\CenaEspecial;
use App\Models\Espacio;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservaController extends Controller
{
    /**
     * Consulta el estado de ocupación de los espacios físicos usando el esquema real.
     */
    public function checkDisponibilidad(Request $request)
    {
        // 1. Validar los datos de entrada
        $request->validate([
            'type' => 'required|string',
            'id' => 'required|integer',
            'fecha' => 'required|date_format:Y-m-d', // Se mapeará contra la columna 'Dia'
        ]);

        $type = $request->type;
        $id = $request->id;
        $fecha = $request->fecha;

        // 2. Filtrar los espacios físicos de la tabla 'Espacios'
        if ($type === Balinesa::class) {
            $espaciosTotales = Espacio::where('Tipo', 'Balinesa')->get();
        } elseif ($type === CenaEspecial::class) {
            $cena = CenaEspecial::find($id);
            if (! $cena) {
                return response()->json(['success' => false, 'message' => 'El paquete de cena no existe.'], 404);
            }
            $espaciosTotales = Espacio::where('Tipo', 'Mesa')
                ->where('Zona', $cena->restaurant)
                ->get();
        } else {
            return response()->json([
                'success' => true,
                'requiere_mapa' => false,
                'message' => 'Este servicio no requiere croquis.',
            ]);
        }

        // 3. Obtener las reservas cruzando con tus columnas reales: 'Dia' y 'Habitacion'
        $reservasHoy = DB::table('Reservas')
            ->where('serviciable_type', $type)
            ->whereDate('Dia', $fecha) // <-- Usando tu columna nativa 'Dia'
            ->whereNotNull('id_espacio')
            ->select('id_espacio', 'Habitacion') // <-- Usando tu columna nativa 'Habitacion'
            ->get()
            ->keyBy('id_espacio');

        // 4. Armar el mapa interactivo estructurado por secciones/zonas
        $mapaDeOcupacion = $espaciosTotales->map(function ($espacio) use ($reservasHoy) {
            $estaOcupado = $reservasHoy->has($espacio->Id);

            return [
                'id_espacio'   => $espacio->Id,
                'nombre'       => $espacio->Nombre, 
                'zona'         => $espacio->Zona, // "Pool Club", "Beach Club", etc.
                'disponible'   => !$estaOcupado,
                'habitacion'   => $estaOcupado ? $reservasHoy->get($espacio->Id)->Habitacion : null,
            ];
        })->groupBy('zona'); // <-- ¡Aquí está el truco! Agrupa los espacios por su columna 'Zona'

        // 5. Retornar la respuesta JSON organizada
        return response()->json([
            'success'        => true,
            'fecha_consulta' => $fecha,
            'requiere_mapa'  => true,
            'secciones'      => $mapaDeOcupacion // Ahora vendrá segmentado por zonas
        ], 200);
    }

    /**
     * Crea una nueva reserva asegurando que el espacio esté libre ese día.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada con tus columnas reales
        $request->validate([
            'serviciable_type' => 'required|string', // 'App\Models\Balinesa' o 'App\Models\CenaEspecial'
            'serviciable_id' => 'required|integer',
            'id_espacio' => 'required|integer', // La cama o mesa física elegida
            'fecha' => 'required|date_format:Y-m-d', // Mapeará a 'Dia'
            'habitacion' => 'required|string',  // Mapeará a 'Habitacion'
            'numero_colaborador_vendedor' => 'required|string',  // Quien usa la iPad
            'usuario_id' => 'required|integer', // ID del capitán/usuario del sistema
        ]);

        $fecha = $request->fecha;
        $idEspacio = $request->id_espacio;

        // 2. CANDADO ANTI-OVERBOOKING: Verificar si ese id_espacio ya se vendió ese mismo DÍA
        $existeReserva = DB::table('Reservas')
            ->where('id_espacio', $idEspacio)
            ->whereDate('Dia', $fecha)
            ->exists();

        if ($existeReserva) {
            return response()->json([
                'success' => false,
                'message' => '¡Lo sentimos! Este espacio ya fue reservado por otra habitación para el día de hoy.',
            ], 422);
        }

        // 3. Insertar la reserva con la anatomía exacta de tu SQL Server
        $reservaId = DB::table('Reservas')->insertGetId([
            'serviciable_type' => $request->serviciable_type,
            'serviciable_id' => $request->serviciable_id,
            'id_espacio' => $idEspacio,
            'Dia' => $fecha,
            'Habitacion' => $request->habitacion,
            'Numero_de_colaborador_vendedor' => $request->numero_colaborador_vendedor,
            'Usuario_id' => $request->usuario_id,
            'Estado' => 'Confirmado',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => '¡Reserva confirmada con éxito!',
            'reserva_id' => $reservaId,
        ], 201);
    }
}
