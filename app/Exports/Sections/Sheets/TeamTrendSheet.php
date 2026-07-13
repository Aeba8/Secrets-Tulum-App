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

class TeamTrendSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $colaboradorTrend;

    public function __construct(array $colaboradorTrend)
    {
        $this->colaboradorTrend = $colaboradorTrend;
    }

    public function title(): string { return 'Tendencia'; }

    public function collection(): Collection
    {
        $rows = [['Tendencia Mensual por Colaborador']];
        $header = ['Colaborador', '#'];
        if (!empty($this->colaboradorTrend[0]['monthly'] ?? [])) {
            foreach ($this->colaboradorTrend[0]['monthly'] as $m) {
                $header[] = $m['month'];
            }
        }
        $rows[] = $header;
        foreach ($this->colaboradorTrend as $col) {
            $row = [$col['colaborador'], '#' . $col['id']];
            foreach ($col['monthly'] as $m) {
                $row[] = $m['amount'];
            }
            $rows[] = $row;
        }
        return collect($rows);
    }

    public function charts()
    {
        $count = count($this->colaboradorTrend);
        if ($count === 0) { return []; }

        $months = count($this->colaboradorTrend[0]['monthly'] ?? []);
        if ($months === 0) { return []; }

        $colStart = 3;
        $colEnd = $colStart + $months - 1;
        $rowStart = 3;
        $rowEnd = $rowStart + $count - 1;

        $colLetters = [];
        for ($i = 0; $i < $months; $i++) {
            $colLetters[] = chr(67 + $i);
        }

        $labels = new DataSeriesValues('String', "'Tendencia'!\$A\${$rowStart}:\$A\${$rowEnd}", null, $count);

        $seriesData = [];
        $seriesLabels = [];
        foreach ($colLetters as $idx => $col) {
            $monthName = $this->colaboradorTrend[0]['monthly'][$idx]['month'] ?? "Mes $idx";
            $seriesLabels[] = new DataSeriesValues('String', "'Tendencia'!\${$col}\$2:\${$col}\$2", null, 1);
            $seriesData[] = new DataSeriesValues('Number', "'Tendencia'!\${$col}\${$rowStart}:\${$col}\${$rowEnd}", null, $count);
        }

        $series = new DataSeries(
            DataSeries::TYPE_LINECHART,
            DataSeries::GROUPING_STANDARD,
            range(0, $months - 1),
            $seriesLabels,
            [$labels],
            $seriesData
        );

        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Tendencia Mensual por Colaborador');
        return new Chart('trend_line', $title, new Legend(), $plotArea);
    }
}
