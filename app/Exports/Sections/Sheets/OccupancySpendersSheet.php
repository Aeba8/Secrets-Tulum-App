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

class OccupancySpendersSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Top Spenders'; }

    public function collection(): Collection
    {
        $rows = [['Top Spenders'], ['Habitación', 'Reservas', 'Revenue']];
        foreach ($this->data['topSpenders'] ?? [] as $s) {
            $rows[] = [$s['habitacion'], $s['reservations'], $s['revenue']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $items = $this->data['topSpenders'] ?? [];
        $count = count($items);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $labels = new DataSeriesValues('String', "'Top Spenders'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Top Spenders'!\$C\$3:\$C\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, 0), [$labels], [$labels], [$vals]);
        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Top Spenders por Revenue');
        return new Chart('spend_bar', $title, new Legend(), $plotArea);
    }
}
