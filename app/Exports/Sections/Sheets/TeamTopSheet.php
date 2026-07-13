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

class TeamTopSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $topCollaborators;

    public function __construct(array $topCollaborators)
    {
        $this->topCollaborators = $topCollaborators;
    }

    public function title(): string { return 'Top 5'; }

    public function collection(): Collection
    {
        $rows = [['Top 5 Colaboradores'], ['Nombre', '#', 'Reservas', 'Monto', 'Eficiencia']];
        foreach ($this->topCollaborators as $col) {
            $rows[] = [$col['name'], $col['id'], $col['reservations'], $col['amount'], $col['efficiency'] . '%'];
        }
        return collect($rows);
    }

    public function charts()
    {
        $count = count($this->topCollaborators);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $labels = new DataSeriesValues('String', "'Top 5'!\$A\$3:\$A\${$lastRow}", null, $count);
        $vals = new DataSeriesValues('Number', "'Top 5'!\$C\$3:\$C\${$lastRow}", null, $count);

        $series = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, 0), [$labels], [$labels], [$vals]);
        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Top 5 - Reservas por Colaborador');
        return new Chart('team_bar', $title, new Legend(), $plotArea);
    }
}
