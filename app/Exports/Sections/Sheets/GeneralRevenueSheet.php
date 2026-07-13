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

class GeneralRevenueSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $revenueByType;

    public function __construct(array $revenueByType)
    {
        $this->revenueByType = $revenueByType;
    }

    public function title(): string
    {
        return 'Revenue por Tipo';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Revenue por Tipo de Servicio'];
        $rows[] = ['Tipo', 'Monto'];
        foreach ($this->revenueByType as $r) {
            $rows[] = [$r['type'], $r['amount']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $count = count($this->revenueByType);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $label = new DataSeriesValues('String', "'Revenue por Tipo'!\$A\$3:\$A\${$lastRow}", null, $count);
        $values = new DataSeriesValues('Number', "'Revenue por Tipo'!\$B\$3:\$B\${$lastRow}", null, $count);

        $series = new DataSeries(
            DataSeries::TYPE_PIECHART,
            null,
            range(0, 0),
            [$label],
            [],
            [$values]
        );

        $layout = new Layout();
        $layout->setShowVal(true);
        $layout->setShowPercent(true);
        $layout->setShowLeaderLines(true);

        $plotArea = new PlotArea($layout, [$series]);
        $title = new Title('Distribución de Revenue por Tipo');
        $legend = new Legend();

        return new Chart('revenue_pie', $title, $legend, $plotArea, true, DataSeries::EMPTY_AS_GAP);
    }
}
