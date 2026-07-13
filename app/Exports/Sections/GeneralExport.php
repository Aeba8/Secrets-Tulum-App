<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sections\Sheets\GeneralKpiSheet;
use App\Exports\Sections\Sheets\GeneralRevenueSheet;
use App\Exports\Sections\Sheets\GeneralBookingSheet;
use App\Exports\Sections\Sheets\GeneralMonthlySheet;

class GeneralExport implements WithMultipleSheets
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
            new GeneralKpiSheet($d['kpis'] ?? []),
            new GeneralRevenueSheet($d['revenueByType'] ?? []),
            new GeneralBookingSheet($d['bookingPace'] ?? []),
            new GeneralMonthlySheet($d['monthlyRevenue'] ?? []),
        ];
    }
}
