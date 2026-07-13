<?php

namespace App\Exports\Sections\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class GeneralKpiSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $kpis;

    public function __construct(array $kpis)
    {
        $this->kpis = $kpis;
    }

    public function title(): string
    {
        return 'KPIs';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Indicadores Clave'];
        $rows[] = ['Métrica', 'Valor', 'Cambio'];
        foreach ($this->kpis as $k) {
            $rows[] = [$k['label'], $k['value'], $k['change']];
        }
        return collect($rows);
    }
}
