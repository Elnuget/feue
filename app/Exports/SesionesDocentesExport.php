<?php

namespace App\Exports;

use App\Models\SesionDocente;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SesionesDocentesExport implements FromCollection, WithHeadings, WithMapping, WithEvents
{
    protected $mes;
    protected $totalHorasPorDocente = [];
    protected $lastRow = 0;

    public function __construct($mes)
    {
        $this->mes = $mes;
    }

    public function collection()
    {
        $fechaInicio = Carbon::createFromFormat('Y-m', $this->mes)->startOfMonth();
        $fechaFin = Carbon::createFromFormat('Y-m', $this->mes)->endOfMonth();

        $query = SesionDocente::with(['docente', 'curso'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin]);

        // Si es docente, solo mostrar sus sesiones
        if (Auth::user()->hasRole('Docente')) {
            $query->where('user_id', Auth::id());
        }

        $sesiones = $query->get()->sortBy('docente.name');

        // Calcular total de horas por docente
        foreach ($sesiones as $sesion) {
            $horaInicio = Carbon::createFromFormat('H:i', $sesion->hora_inicio);
            $horaFin = Carbon::createFromFormat('H:i', $sesion->hora_fin);
            $minutosTotales = $horaFin->diffInMinutes($horaInicio);
            
            if (!isset($this->totalHorasPorDocente[$sesion->docente->name])) {
                $this->totalHorasPorDocente[$sesion->docente->name] = 0;
            }
            $this->totalHorasPorDocente[$sesion->docente->name] += $minutosTotales;
        }

        $this->lastRow = count($sesiones) + 2; // +2 por el encabezado y el índice base 1
        return $sesiones;
    }

    public function headings(): array
    {
        return [
            'Docente',
            'Curso',
            'Fecha',
            'Hora Inicio',
            'Hora Fin',
            'Horas',
            'Aula',
            'Materia',
            'Tema Impartido',
            'Observaciones'
        ];
    }

    public function map($sesion): array
    {
        // Calcular la diferencia en horas
        $horaInicio = Carbon::createFromFormat('H:i', $sesion->hora_inicio);
        $horaFin = Carbon::createFromFormat('H:i', $sesion->hora_fin);
        $diferencia = $horaFin->diffInHours($horaInicio) . ':' . str_pad($horaFin->diffInMinutes($horaInicio) % 60, 2, '0', STR_PAD_LEFT);

        return [
            $sesion->docente->name,
            $sesion->curso->nombre,
            $sesion->fecha->format('d/m/Y'),
            $sesion->hora_inicio,
            $sesion->hora_fin,
            $diferencia,
            $sesion->aula ?? 'N/A',
            $sesion->materia ?? 'N/A',
            $sesion->tema_impartido ?? 'N/A',
            $sesion->observaciones ?? 'N/A'
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $currentRow = $this->lastRow + 2; // Dejamos una fila en blanco

                // Agregar totales por docente
                foreach ($this->totalHorasPorDocente as $docente => $minutos) {
                    $horas = floor($minutos / 60);
                    $minutosRestantes = $minutos % 60;
                    $totalFormateado = $horas . ':' . str_pad($minutosRestantes, 2, '0', STR_PAD_LEFT);

                    $event->sheet->setCellValue('A' . $currentRow, 'Total ' . $docente);
                    $event->sheet->setCellValue('F' . $currentRow, $totalFormateado);
                    
                    // Estilo para la fila de total
                    $event->sheet->getStyle('A' . $currentRow . ':F' . $currentRow)->applyFromArray([
                        'font' => ['bold' => true],
                        'fill' => [
                            'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                            'startColor' => ['rgb' => 'E8E8E8']
                        ]
                    ]);
                    
                    $currentRow++;
                }

                // Agregar título del mes
                $mesFormateado = Carbon::createFromFormat('Y-m', $this->mes)->isoFormat('MMMM YYYY');
                $event->sheet->mergeCells('A' . ($currentRow + 1) . ':J' . ($currentRow + 1));
                $event->sheet->setCellValue('A' . ($currentRow + 1), 'Reporte del mes de ' . ucfirst($mesFormateado));
                $event->sheet->getStyle('A' . ($currentRow + 1))->applyFromArray([
                    'font' => ['bold' => true, 'size' => 14],
                    'alignment' => ['horizontal' => 'center']
                ]);

                // Estilo para el encabezado
                $event->sheet->getStyle('A1:J1')->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4B88E5']
                    ],
                    'font' => [
                        'color' => ['rgb' => 'FFFFFF']
                    ]
                ]);

                // Auto-ajustar columnas
                foreach(range('A','J') as $column) {
                    $event->sheet->getColumnDimension($column)->setAutoSize(true);
                }
            }
        ];
    }
} 