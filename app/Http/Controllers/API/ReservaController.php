<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Balinesa;
use App\Models\CenaEspecial;
use App\Models\Espacio;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReservaController extends Controller
{
    /**
     * Consulta el estado de ocupación de los espacios físicos usando el esquema real.
     */
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

        try {
            // 2. Filtrar los espacios físicos de la tabla 'Espacios' (SOLO LOS ACTIVOS)
            if ($type === Balinesa::class) {
                $espaciosTotales = Espacio::where('Tipo', 'Balinesa')
                    ->where('Is_Active', true) // 🌟 Filtro para ocultar los 'False'
                    ->get();
            } elseif ($type === CenaEspecial::class) {
                $cena = CenaEspecial::find($id);
                if (! $cena) {
                    return response()->json(['success' => false, 'message' => 'El paquete de cena no existe.'], 404);
                }
                $espaciosTotales = Espacio::where('Tipo', 'Mesa')
                    ->where('Zona', $cena->restaurant)
                    ->where('Is_Active', true) // 🌟 Filtro para ocultar los 'False'
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
                ->whereDate('Dia', $fecha) // <-- Usando tu columna nativa 'Dia'
                ->whereNotNull('id_espacio')
                ->whereNotIn('Estado', ['Cancelado', 'No-Show'])
                ->select('id_espacio', 'Habitacion') // <-- Usando tu columna nativa 'Habitacion'
                ->get()
                ->keyBy('id_espacio');

            // 4. Armar el mapa interactivo estructurado por secciones/zonas
            $mapaDeOcupacion = $espaciosTotales->map(function ($espacio) use ($reservasHoy) {
                $estaOcupado = $reservasHoy->has($espacio->Id);

                return [
                    'id_espacio' => $espacio->Id,
                    'nombre' => $espacio->Nombre,
                    'zona' => $espacio->Zona, // "Pool Club", "Beach Club", etc.
                    'disponible' => ! $estaOcupado,
                    'habitacion' => $estaOcupado ? $reservasHoy->get($espacio->Id)->Habitacion : null,
                ];
            })->groupBy('zona'); // Agrupa los espacios por su columna 'Zona'

            // 5. Retornar la respuesta JSON organizada
            return response()->json([
                'success' => true,
                'fecha_consulta' => $fecha,
                'requiere_mapa' => true,
                'secciones' => $mapaDeOcupacion, // Ahora vendrá segmentado por zonas
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error en checkDisponibilidad: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error interno al consultar la disponibilidad.',
            ], 500);
        }
    }

    /**
     * Crea una nueva reserva asegurando que el espacio esté libre ese día.
     */
    public function store(Request $request)
    {
        // 1. Validar los datos de entrada con tus columnas reales (usuario_id ya no se requiere en el request)
        $request->validate([
            'serviciable_type' => 'required|string|in:App\Models\Balinesa,App\Models\CenaEspecial,App\Models\Experiencia',
            'serviciable_id' => 'required|integer',
            'id_espacio' => 'required|integer', // La cama o mesa física elegida
            'fecha' => 'required|date_format:Y-m-d', // Mapeará a 'Dia'
            'habitacion' => 'required|string',  // Mapeará a 'Habitacion'
            'numero_colaborador_vendedor' => 'required|string',  // Quien usa la iPad
            'observaciones' => 'nullable|string', // Adaptado para notas opcionales de la reserva
        ]);

        // Captura automática del ID del usuario autenticado (con fallback al ID 1 para desarrollo)
        $usuarioLogueadoId = auth()->id() ?? 1;

        $fecha = $request->fecha;
        $idEspacio = $request->id_espacio;

        // Forzar espacio Genérico para Experiencias (no tienen espacio físico)
        if ($request->serviciable_type === 'App\Models\Experiencia') {
            $idEspacio = Espacio::where('Nombre', 'Sin Espacio Físico')->value('Id') ?? 1;
        }

        try {
            $reservaId = DB::transaction(function () use ($request, $idEspacio, $fecha, $usuarioLogueadoId) {
                // 2. CANDADO ANTI-OVERBOOKING: Verificar si ese id_espacio ya se vendió ese mismo DÍA
                if ($request->serviciable_type !== 'App\Models\Experiencia') {
                    $existeReserva = DB::table('Reservas')
                        ->where('id_espacio', $idEspacio)
                        ->whereDate('Dia', $fecha)
                        ->whereNotIn('Estado', ['Cancelado', 'No-Show'])
                        ->lockForUpdate()
                        ->exists();

                    if ($existeReserva) {
                        throw new \Illuminate\Http\Exceptions\HttpResponseException(
                            response()->json([
                                'success' => false,
                                'message' => '¡Lo sentimos! Este espacio ya fue reservado por otra habitación para el día de hoy.',
                            ], 422)
                        );
                    }
                }

                // 3. Insertar la reserva con la anatomía exacta de tu SQL Server
                return DB::table('Reservas')->insertGetId([
                    'serviciable_type' => $request->serviciable_type,
                    'serviciable_id' => $request->serviciable_id,
                    'id_espacio' => $idEspacio,
                    'Dia' => $fecha,
                    'Habitacion' => $request->habitacion,
                    'Numero_de_colaborador_vendedor' => $request->numero_colaborador_vendedor,
                    'Usuario_id' => $usuarioLogueadoId,
                    'Estado' => 'Confirmado',
                    'Observaciones' => $request->observaciones,
                    'created_at' => now(),
                    'updated_at' => now(),
                ], 'Id');
            });

            return response()->json([
                'success' => true,
                'message' => '¡Reserva confirmada con éxito!',
                'reserva_id' => $reservaId,
            ], 201);

        } catch (\Illuminate\Http\Exceptions\HttpResponseException $e) {
            return $e->getResponse();
        } catch (\Exception $e) {
            Log::error('Error al guardar reserva', ['exception' => $e]);

            return response()->json([
                'success' => false,
                'message' => 'Hubo un problema de comunicación con la base de datos. Intente de nuevo.',
            ], 500);
        }
    }
}
