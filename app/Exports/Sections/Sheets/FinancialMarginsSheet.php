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

class FinancialMarginsSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Márgenes'; }

    public function collection(): Collection
    {
        $rows = [['Distribución de Márgenes'], ['Rango', 'Paquetes']];
        foreach ($this->data['marginDistribution'] ?? [] as $m) {
            $rows[] = [$m['range'], $m['count']];
        }
        $rows[] = [];
        $rows[] = ['Ticket por Categoría', 'Valor'];
        foreach ($this->data['ticketByCategory'] ?? [] as $t) {
            $rows[] = [$t['label'], $t['value']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $items = $this->data['marginDistribution'] ?? [];
        $count = count($items);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $labels = new DataSeriesValues('String', "'Márgenes'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Márgenes'!\$B\$3:\$B\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, 0), [$labels], [$labels], [$vals]);
        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Distribución de Márgenes');
        return new Chart('margin_bar', $title, new Legend(), $plotArea);
    }
}
