<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sections\Sheets\FinancialRevenueSheet;
use App\Exports\Sections\Sheets\FinancialTopSheet;
use App\Exports\Sections\Sheets\FinancialWeeklySheet;
use App\Exports\Sections\Sheets\FinancialMonthlySheet;
use App\Exports\Sections\Sheets\FinancialMarginsSheet;

class FinancialExport implements WithMultipleSheets
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
            new FinancialRevenueSheet($d),
            new FinancialTopSheet($d),
            new FinancialWeeklySheet($d),
            new FinancialMonthlySheet($d),
            new FinancialMarginsSheet($d),
        ];
    }
}
