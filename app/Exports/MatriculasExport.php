<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MatriculasExport implements FromCollection, WithHeadings
{
    protected $matriculas;

    public function __construct($matriculas)
    {
        $this->matriculas = $matriculas;
    }

    public function collection()
    {
        return $this->matriculas->map(function ($matricula, $index) {
            return [
                '#' => $index + 1,
                'Nombre del Matriculado' => $matricula->usuario->name,
            ];
        });
    }

    public function headings(): array
    {
        return [
            '#',
            'Nombre del Matriculado',
        ];
    }
}