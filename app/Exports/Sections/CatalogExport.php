<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class CatalogExport implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function title(): string
    {
        return 'Catálogo';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Catálogo de Paquetes'];
        $rows[] = ['Tipo', 'Nombre', 'Precio', 'Costo Operativo', 'Estado', 'Capacidad', 'Categoría'];
        foreach ($this->data['balinesas'] ?? [] as $b) {
            $rows[] = ['Balinesa', $b->Nombre ?? '', $b->Precio ?? 0, $b->Costo_Operativo ?? 0, $b->Estado ?? '', $b->capacidad_maxima ?? '', $b->categoria?->Nombre ?? ''];
        }
        foreach ($this->data['cenas'] ?? [] as $c) {
            $rows[] = ['Cena', $c->Nombre ?? '', $c->Precio ?? 0, $c->Costo_Operativo ?? 0, $c->Estado ?? '', $c->numero_personas ?? '', $c->categoria?->Nombre ?? ''];
        }
        foreach ($this->data['experiencias'] ?? [] as $e) {
            $rows[] = ['Experiencia', $e->Nombre ?? '', $e->Precio ?? 0, $e->Costo_Operativo ?? 0, $e->Estado ?? '', $e->numero_personas ?? '', $e->categoria?->Nombre ?? ''];
        }
        return collect($rows);
    }
}
