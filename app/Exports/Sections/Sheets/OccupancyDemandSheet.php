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

class OccupancyDemandSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $data;

    public function __construct($data) { $this->data = $data; }

    public function title(): string { return 'Calendario Demanda'; }

    public function collection(): Collection
    {
        $rows = [['Calendario Demanda (próximos 30 días)'], ['Fecha', 'Reservas', 'Intensidad']];
        foreach ($this->data['demandCalendar'] ?? [] as $cal) {
            $rows[] = [$cal['date'], $cal['count'], $cal['intensity']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $items = $this->data['demandCalendar'] ?? [];
        $count = count($items);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $labels = new DataSeriesValues('String', "'Calendario Demanda'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Calendario Demanda'!\$B\$3:\$B\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_LINECHART, DataSeries::GROUPING_STANDARD, range(0, 0), [$labels], [$labels], [$vals]);
        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Demanda Diaria (Próximos 30 días)');
        return new Chart('demand_line', $title, new Legend(), $plotArea);
    }
}
