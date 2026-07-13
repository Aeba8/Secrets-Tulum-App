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

class FinancialWeeklySheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Comparación Semanal'; }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Comparación Semanal vs Revenue por Día'];
        $rows[] = ['Semana / Día', 'Revenue'];
        foreach ($this->data['weeklyComparison'] ?? [] as $w) {
            $rows[] = [$w['week'], $w['revenue']];
        }
        $rows[] = [];
        $rows[] = ['Revenue por Día de la Semana', 'Monto'];
        foreach ($this->data['revenueByDayOfWeek'] ?? [] as $w) {
            $rows[] = [$w['day'], $w['amount']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $items = $this->data['weeklyComparison'] ?? [];
        $count = count($items);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $labels = new DataSeriesValues('String', "'Comparación Semanal'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Comparación Semanal'!\$B\$3:\$B\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, 0), [$labels], [$labels], [$vals]);
        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Comparación Semanal de Revenue');
        return new Chart('weekly_bar', $title, new Legend(), $plotArea);
    }
}
