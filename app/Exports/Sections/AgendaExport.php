<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class AgendaExport implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $reservations;

    public function __construct($reservations)
    {
        $this->reservations = $reservations;
    }

    public function title(): string
    {
        return 'Agenda';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Agenda de Reservas'];
        $rows[] = ['#', 'Fecha', 'Habitación', 'Servicio', 'Tipo', 'Espacio', 'Colaborador', 'Estado', 'Observaciones', 'Creado'];
        foreach ($this->reservations as $r) {
            $rows[] = [
                $r->Id,
                $r->Dia ? date('d/m/Y', strtotime($r->Dia)) : '',
                $r->Habitacion,
                $r->serviciable?->Nombre ?? $r->serviciable?->name ?? 'N/A',
                class_basename($r->serviciable_type ?? ''),
                $r->espacio?->Nombre ?? 'N/A',
                $r->Numero_de_colaborador_vendedor ?? '',
                $r->Estado ?? '',
                $r->Observaciones ?? '',
                $r->created_at ? $r->created_at->format('d/m/Y H:i') : '',
            ];
        }
        return collect($rows);
    }
}
