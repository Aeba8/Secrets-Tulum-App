<?php

namespace App\Exports\Sections;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use App\Exports\Concerns\WithSheetStyling;
use Illuminate\Support\Collection;

class UsuariosExport implements FromCollection, WithTitle, ShouldAutoSize, WithStyles, WithEvents
{
    use WithSheetStyling;

    protected $usuarios;

    public function __construct($usuarios)
    {
        $this->usuarios = $usuarios;
    }

    public function title(): string
    {
        return 'Usuarios';
    }

    public function collection(): Collection
    {
        $rows = [];
        $rows[] = ['Usuarios Operativos'];
        $rows[] = ['#', 'Nombre', 'Email', '# Colaborador', 'Estado'];
        foreach ($this->usuarios as $u) {
            $rows[] = [
                $u->Id,
                $u->Nombre ?? '',
                $u->Email ?? '',
                $u->Numero_de_colaborador ?? '',
                $u->Estado ?? '',
            ];
        }
        return collect($rows);
    }
}
