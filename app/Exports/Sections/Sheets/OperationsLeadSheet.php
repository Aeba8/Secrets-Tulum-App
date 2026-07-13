<?php

namespace App\Exports\Sections\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class OperationsLeadSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Lead Time'; }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Lead Time Promedio'];
        $rows[] = ['Métrica', 'Valor'];
        $rows[] = ['Lead Time Promedio (días)', $this->data['avgLeadTime'] ?? 'N/A'];
        return collect($rows);
    }
}
