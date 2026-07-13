<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Espacio;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgendaController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'serviciable_type' => 'required|string|in:App\Models\Balinesa,App\Models\CenaEspecial,App\Models\Experiencia',
            'serviciable_id' => 'required|integer',
            'id_espacio' => 'nullable|integer',
            'fecha' => 'required|date_format:Y-m-d',
            'habitacion' => 'required|digits:4',
            'estado' => 'required|string|max:30',
            'colaborador' => ['nullable', 'digits:6'],
            'observaciones' => ['nullable', 'string', function ($attr, $value, $fail) {
                if (str_word_count($value) > 100) {
                    $fail('Las observaciones no pueden exceder 100 palabras.');
                }
            }],
        ]);

        $id_espacio = $validated['id_espacio'] ?? null;
        if ($id_espacio === null && $validated['serviciable_type'] === 'App\Models\Experiencia') {
            $id_espacio = Espacio::where('Nombre', 'Genérico')->value('Id') ?? 1;
        }

        DB::table('Reservas')->insert([
            'serviciable_type' => $validated['serviciable_type'],
            'serviciable_id' => $validated['serviciable_id'],
            'id_espacio' => $id_espacio,
            'Dia' => $validated['fecha'],
            'Habitacion' => $validated['habitacion'],
            'Numero_de_colaborador_vendedor' => $validated['colaborador'] ?? '',
            'Usuario_id' => auth()->id() ?? 1,
            'Estado' => $validated['estado'],
            'Observaciones' => $validated['observaciones'] ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect(route('admin.dashboard') . '#agenda')
            ->with('success', 'Reserva creada correctamente.');
    }

    public function update(Request $request, $id)
    {
        $reserva = Reserva::findOrFail($id);

        $validated = $request->validate([
            'serviciable_type' => 'required|string|in:App\Models\Balinesa,App\Models\CenaEspecial,App\Models\Experiencia',
            'serviciable_id' => 'required|integer',
            'id_espacio' => 'nullable|integer',
            'fecha' => 'required|date_format:Y-m-d',
            'habitacion' => 'required|digits:4',
            'estado' => 'required|string|max:30',
            'colaborador' => ['nullable', 'digits:6'],
            'observaciones' => ['nullable', 'string', function ($attr, $value, $fail) {
                if (str_word_count($value) > 100) {
                    $fail('Las observaciones no pueden exceder 100 palabras.');
                }
            }],
        ]);

        $id_espacio = $validated['id_espacio'] ?? $reserva->id_espacio;
        if ($id_espacio === null && $validated['serviciable_type'] === 'App\Models\Experiencia') {
            $id_espacio = Espacio::where('Nombre', 'Genérico')->value('Id') ?? 1;
        }

        DB::table('Reservas')
            ->where('Id', $id)
            ->update([
                'serviciable_type' => $validated['serviciable_type'],
                'serviciable_id' => $validated['serviciable_id'],
                'id_espacio' => $id_espacio,
                'Dia' => $validated['fecha'],
                'Habitacion' => $validated['habitacion'],
                'Numero_de_colaborador_vendedor' => $validated['colaborador'] ?? '',
                'Estado' => $validated['estado'],
                'Observaciones' => $validated['observaciones'] ?? '',
                'updated_at' => now(),
            ]);

        return redirect(route('admin.dashboard') . '#agenda')
            ->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy($id)
    {
        $reserva = Reserva::findOrFail($id);
        DB::table('Reservas')
            ->where('Id', $id)
            ->update(['Estado' => 'Cancelado', 'updated_at' => now()]);

        return redirect(route('admin.dashboard') . '#agenda')
            ->with('success', 'Reserva cancelada correctamente.');
    }

    public function activate($id)
    {
        $reserva = Reserva::findOrFail($id);
        DB::table('Reservas')
            ->where('Id', $id)
            ->update(['Estado' => 'Confirmado', 'updated_at' => now()]);

        return redirect(route('admin.dashboard') . '#agenda')
            ->with('success', 'Reserva reactivada correctamente.');
    }
}
