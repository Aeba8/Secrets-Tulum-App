<?php

namespace App\Exports\Sections\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class OperationsAlertsSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Alertas'; }

    public function collection(): Collection
    {
        $rows = [['Alertas Operativas'], ['Severidad', 'Zona', 'Mensaje']];
        foreach ($this->data['alerts'] ?? [] as $a) {
            $rows[] = [$a['severity'] ?? '', $a['zone'] ?? '', $a['message'] ?? ''];
        }
        return collect($rows);
    }
}
