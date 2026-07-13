<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sections\Sheets\BcgSummarySheet;
use App\Exports\Sections\Sheets\BcgProductsSheet;

class BcgExport implements WithMultipleSheets
{
    protected $products;
    protected $summary;

    public function __construct(array $products, array $summary)
    {
        $this->products = $products;
        $this->summary = $summary;
    }

    public function sheets(): array
    {
        return [
            new BcgSummarySheet($this->summary),
            new BcgProductsSheet($this->products),
        ];
    }
}
