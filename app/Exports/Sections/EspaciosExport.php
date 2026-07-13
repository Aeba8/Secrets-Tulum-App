<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class EspaciosExport implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $espacios;

    public function __construct($espacios)
    {
        $this->espacios = $espacios;
    }

    public function title(): string
    {
        return 'Espacios';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Espacios Activos'];
        $rows[] = ['#', 'Nombre', 'Tipo', 'Zona', 'Capacidad', 'Estado'];
        foreach ($this->espacios as $e) {
            $rows[] = [
                $e->Id,
                $e->Nombre ?? '',
                $e->Tipo ?? '',
                $e->Zona ?? '',
                $e->Capacidad ?? '',
                $e->Is_Active ? 'Activo' : 'Inactivo',
            ];
        }
        return collect($rows);
    }
}
