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

class GeneralBookingSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $bookingPace;

    public function __construct(array $bookingPace)
    {
        $this->bookingPace = $bookingPace;
    }

    public function title(): string
    {
        return 'Booking Pace';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Booking Pace (día a día)'];
        $rows[] = ['Fecha', 'Reservas Reales', 'Promedio'];
        foreach ($this->bookingPace as $b) {
            $rows[] = [$b['day'], $b['actual'], $b['average']];
        }
        return collect($rows);
    }

    public function charts()
    {
        $count = count($this->bookingPace);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $label = new DataSeriesValues('String', "'Booking Pace'!\$A\$3:\$A\${$lastRow}", null, $count);
        $actual = new DataSeriesValues('Number', "'Booking Pace'!\$B\$3:\$B\${$lastRow}", null, $count);
        $average = new DataSeriesValues('Number', "'Booking Pace'!\$C\$3:\$C\${$lastRow}", null, $count);

        $series = new DataSeries(
            DataSeries::TYPE_LINECHART,
            DataSeries::GROUPING_STANDARD,
            range(0, 1),
            [$label, $label],
            [$label],
            [$actual, $average]
        );

        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Booking Pace Diario');
        $legend = new Legend();

        return new Chart('booking_line', $title, $legend, $plotArea, true, DataSeries::EMPTY_AS_GAP);
    }
}
