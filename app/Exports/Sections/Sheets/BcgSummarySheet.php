<?php

namespace App\Exports\Sections\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class BcgSummarySheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $summary;

    public function __construct(array $summary)
    {
        $this->summary = $summary;
    }

    public function title(): string
    {
        return 'Resumen BCG';
    }

    public function collection(): Collection
    {
        $s = $this->summary;
        $rows = [];
        $rows[] = ['Resumen BCG'];
        $rows[] = ['Indicador', 'Valor'];
        $rows[] = ['Paquetes Activos', $s['total_products'] ?? 0];
        $rows[] = ['Revenue Total (30d)', $s['total_revenue'] ?? 0];
        $rows[] = ['Ganancias Totales', $s['total_profit'] ?? 0];
        $rows[] = ['Más Rentable', $s['most_profitable'] ?? 'N/A'];
        return collect($rows);
    }
}
