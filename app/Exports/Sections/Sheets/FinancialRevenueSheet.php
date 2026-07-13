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
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class FinancialRevenueSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Revenue Tipo'; }

    public function collection(): Collection
    {
        $rows = [['Revenue por Tipo de Servicio'], ['Tipo', 'Monto', '%']];
        foreach ($this->data['revenueByType'] ?? [] as $r) {
            $rows[] = [$r['type'], $r['amount'], $r['percentage'] . '%'];
        }
        return collect($rows);
    }

    public function charts()
    {
        $items = $this->data['revenueByType'] ?? [];
        $count = count($items);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $label = new DataSeriesValues('String', "'Revenue Tipo'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Revenue Tipo'!\$B\$3:\$B\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_DOUGHNUTCHART, null, range(0, 0), [$label], [], [$vals]);
        $layout = new Layout();
        $layout->setShowVal(true);
        $layout->setShowPercent(true);
        $plotArea = new PlotArea($layout, [$series]);
        $title = new Title('Revenue por Tipo');
        return new Chart('fin_rev_pie', $title, new Legend(), $plotArea);
    }
}
