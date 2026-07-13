<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sections\Sheets\OperationsLeadSheet;
use App\Exports\Sections\Sheets\OperationsUtilizationSheet;
use App\Exports\Sections\Sheets\OperationsAlertsSheet;

class OperationsExport implements WithMultipleSheets
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
            new OperationsLeadSheet($d),
            new OperationsUtilizationSheet($d),
            new OperationsAlertsSheet($d),
        ];
    }
}
