<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sections\Sheets\TeamTopSheet;
use App\Exports\Sections\Sheets\TeamTrendSheet;

class TeamExport implements WithMultipleSheets
{
    protected $topCollaborators;
    protected $colaboradorTrend;

    public function __construct(array $topCollaborators, array $colaboradorTrend)
    {
        $this->topCollaborators = $topCollaborators;
        $this->colaboradorTrend = $colaboradorTrend;
    }

    public function sheets(): array
    {
        return [
            new TeamTopSheet($this->topCollaborators),
            new TeamTrendSheet($this->colaboradorTrend),
        ];
    }
}
