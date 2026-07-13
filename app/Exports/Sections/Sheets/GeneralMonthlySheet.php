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

class GeneralMonthlySheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $monthlyRevenue;

    public function __construct(array $monthlyRevenue)
    {
        $this->monthlyRevenue = $monthlyRevenue;
    }

    public function title(): string
    {
        return 'Revenue Mensual';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Revenue Mensual (últimos 6 meses)'];
        $rows[] = ['Mes', 'Monto'];
        foreach ($this->monthlyRevenue as $m) {
            $rows[] = [$m['month'], $m['amount']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $count = count($this->monthlyRevenue);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $label = new DataSeriesValues('String', "'Revenue Mensual'!\$A\$3:\$A\${$lastRow}", null, $count);
        $values = new DataSeriesValues('Number', "'Revenue Mensual'!\$B\$3:\$B\${$lastRow}", null, $count);

        $series = new DataSeries(
            DataSeries::TYPE_LINECHART,
            DataSeries::GROUPING_STANDARD,
            range(0, 0),
            [$label],
            [$label],
            [$values]
        );

        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Tendencia de Revenue Mensual');
        $legend = new Legend();

        return new Chart('monthly_line', $title, $legend, $plotArea, true, DataSeries::EMPTY_AS_GAP);
    }
}
