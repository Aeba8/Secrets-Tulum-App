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
use PhpOffice\PhpSpreadsheet\Chart\Axis;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class BcgProductsSheet implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents, WithCharts
{
    use WithSheetStyling;

    protected $products;

    public function __construct(array $products)
    {
        $this->products = $products;
    }

    public function title(): string
    {
        return 'Productos BCG';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Matriz BCG - Productos'];
        $rows[] = ['#', 'Producto', 'Tipo', 'Categoría', 'Crecimiento %', 'Participación %', 'Revenue', 'Margen %', 'Reservas', 'Cuadrante', 'Recomendación'];
        foreach ($this->products as $i => $p) {
            $rows[] = [
                $i + 1, $p['name'], $p['type'], $p['category'],
                $p['growth'], $p['share'], $p['revenue'], $p['margin'],
                $p['count'], $p['quadrant'], $p['recommendation'],
            ];
        }
        return collect($rows);
    }

    public function charts()
    {
        $count = count($this->products);
        if ($count === 0) { return []; }

        $lastRow = $count + 2;
        $growth = new DataSeriesValues('Number', "'Productos BCG'!\$E\$3:\$E\${$lastRow}", null, $count);
        $share = new DataSeriesValues('Number', "'Productos BCG'!\$F\$3:\$F\${$lastRow}", null, $count);
        $names = new DataSeriesValues('String', "'Productos BCG'!\$B\$3:\$B\${$lastRow}", null, $count);

        $series = new DataSeries(
            DataSeries::TYPE_SCATTERCHART,
            null,
            range(0, 0),
            [$names],
            [$share],
            [$growth]
        );

        $plotArea = new PlotArea(null, [$series]);
        $title = new Title('Matriz BCG: Crecimiento vs Participación');
        $legend = new Legend();

        return new Chart('bcg_scatter', $title, $legend, $plotArea, true, DataSeries::EMPTY_AS_GAP);
    }
}
