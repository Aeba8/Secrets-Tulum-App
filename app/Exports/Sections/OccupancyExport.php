<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sections\Sheets\OccupancyZoneSheet;
use App\Exports\Sections\Sheets\OccupancyPeakSheet;
use App\Exports\Sections\Sheets\OccupancySpendersSheet;
use App\Exports\Sections\Sheets\OccupancyDemandSheet;

class OccupancyExport implements WithMultipleSheets
{
    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function sheets(): array
    {
        $d = $this->data;
        return [
            new OccupancyZoneSheet($d),
            new OccupancyPeakSheet($d),
            new OccupancySpendersSheet($d),
            new OccupancyDemandSheet($d),
        ];
    }
}
