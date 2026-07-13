<?php

namespace App\Exports\Sections\Sheets;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithCharts;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class OperationsUtilizationSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Utilización'; }

    public function collection(): Collection
    {
        $rows = [['Utilización de Espacios (30 días)'], ['Nombre', 'Tipo', 'Zona', 'Capacidad', 'Días Ocupados', 'Utilización %']];
        foreach ($this->data['spaceUtilization'] ?? [] as $s) {
            $rows[] = [$s['nombre'], $s['tipo'], $s['zona'], $s['capacidad'], $s['dias_ocupados'], $s['utilizacion']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $items = $this->data['spaceUtilization'] ?? [];
        $count = count($items);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $labels = new DataSeriesValues('String', "'Utilización'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Utilización'!\$F\$3:\$F\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, 0), [$labels], [$labels], [$vals]);
        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Utilización de Espacios (%)');
        return new Chart('util_bar', $title, new Legend(), $plotArea);
    }
}
