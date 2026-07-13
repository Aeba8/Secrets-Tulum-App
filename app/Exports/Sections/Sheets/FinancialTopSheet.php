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

class FinancialTopSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Top Productos'; }

    public function collection(): Collection
    {
        $rows = [['Top Paquetes por Revenue'], ['Nombre', 'Tipo', 'Revenue', 'Margen %']];
        foreach ($this->data['topPackages'] ?? [] as $p) {
            $rows[] = [$p['name'], $p['type'], $p['revenue'], $p['margin']];
        }
        $rows[] = [];
        $rows[] = ['Top por Cantidad de Reservas', 'Tipo', 'Reservas'];
        foreach ($this->data['topByCount'] ?? [] as $p) {
            $rows[] = [$p['name'], $p['type'], $p['count']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $items = $this->data['topPackages'] ?? [];
        $count = count($items);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $names = new DataSeriesValues('String', "'Top Productos'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Top Productos'!\$C\$3:\$C\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, 0), [$names], [$names], [$vals]);
        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Top 5 Paquetes por Revenue');
        return new Chart('top_bar', $title, new Legend(), $plotArea);
    }
}
