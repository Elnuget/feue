<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Curso;    // Add this line
use App\Models\TipoCurso; // Add this line
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\MatriculaAprobada;
use Barryvdh\DomPDF\Facade\Pdf as PDF; // Update the namespace
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\MatriculasExport;
use App\Exports\UsersExport;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use Endroid\QrCode\QrCode as EndroidQrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use App\Models\IntentoCuestionario;
use App\Models\AulaVirtual;

class MatriculaController extends Controller
{
    public function index(Request $request)
    {
        $query = Matricula::query()->with('usuario');
        
        if (!auth()->user()->hasRole(1)) {
            $query->where('usuario_id', auth()->id());
        }

        $tiposCursos = TipoCurso::all();
        $tipoCursoId = $request->query('tipo_curso');
        $cursoId = $request->query('curso_id');
        $search = $request->query('search'); // Add this line

        // Get courses based on tipo_curso
        if ($tipoCursoId) {
            $cursos = Curso::where('tipo_curso_id', $tipoCursoId)->get(); // Fetch all courses regardless of status
            if ($cursoId) {
                $query->where('curso_id', $cursoId);
            } else {
                $query->whereIn('curso_id', $cursos->pluck('id'));
            }
        } else {
            $cursos = Curso::all(); // Fetch all courses regardless of status
        }

        // Add search filter
        if ($search) {
            $query->whereHas('usuario', function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%');
            });
        }

        // Add join with users table and sort by name
        $matriculas = $query->join('users', 'matriculas.usuario_id', '=', 'users.id')
                           ->select('matriculas.*')
                           ->orderBy('users.name')
                           ->get();

        return view('matriculas.index', compact('matriculas', 'tiposCursos', 'cursos', 'tipoCursoId', 'cursoId', 'search'));
    }

    public function create(Request $request)
    {
        if (auth()->user()->hasRole(1)) {
            $usuarios = \App\Models\User::all();
        } else {
            $usuarios = \App\Models\User::where('id', auth()->id())->get();
        }
        $cursos = \App\Models\Curso::where('estado', 'Activo')->get(); // Only get active courses
        $cursosPorTipo = $cursos->groupBy('tipo_curso_id'); // Group courses by type
        $cursoSeleccionado = $request->input('curso_id');
        $tipoCursoSeleccionado = $cursoSeleccionado ? \App\Models\Curso::find($cursoSeleccionado)->tipo_curso_id : null;
        $universidades = \App\Models\Universidad::all();
        $tiposCursos = \App\Models\TipoCurso::all(); // Add this line
        return view('matriculas.create', compact('usuarios', 'cursosPorTipo', 'cursoSeleccionado', 'tipoCursoSeleccionado', 'universidades', 'tiposCursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
            'fecha_matricula' => 'required|date',
            'monto_total' => 'required|numeric',
            'valor_pendiente' => 'nullable|numeric',
            'estado_matricula' => 'required|in:Pendiente,Aprobada,Completada,Rechazada',
            'universidad_id' => 'required_if:curso_id,1,2,3|exists:universidades,id',
        ]);

        // Check if user is already enrolled in this course
        $existingMatricula = Matricula::where('usuario_id', $request->usuario_id)
            ->where('curso_id', $request->curso_id)
            ->first();

        if ($existingMatricula) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'El usuario ya está matriculado en este curso.');
        }

        if (!auth()->user()->hasRole(1) && $request->usuario_id != auth()->id()) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para crear esta matrícula.');
        }

        $data = $request->all();
        $data['valor_pendiente'] = $data['monto_total'];

        // Set the status to approved
        $data['estado_matricula'] = 'Aprobada';

        Matricula::create($data);

        // Check for existing record before saving the university selection
        if (in_array($request->curso_id, [1, 2, 3])) {
            $existingAspiracion = \App\Models\UserAspiracion::where('user_id', $request->usuario_id)
                ->where('universidad_id', $request->universidad_id)
                ->first();

            if (!$existingAspiracion) {
                \App\Models\UserAspiracion::create([
                    'user_id' => $request->usuario_id,
                    'universidad_id' => $request->universidad_id,
                ]);
            }
        }

        return redirect()->route('matriculas.index')->with('success', 'Matricula creada exitosamente.');
    }

    public function show(Matricula $matricula)
    {
        if (!auth()->user()->hasRole(1) && $matricula->usuario_id != auth()->id()) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para ver esta matrícula.');
        }
        $pagos = $matricula->pagos; // Fetch associated payments
        return view('matriculas.show', compact('matricula', 'pagos'));
    }

    public function edit(Matricula $matricula)
    {
        if (!auth()->user()->hasRole(1) && $matricula->usuario_id != auth()->id()) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para editar esta matrícula.');
        }

        if (auth()->user()->hasRole(1)) {
            $usuarios = \App\Models\User::all();
        } else {
            $usuarios = \App\Models\User::where('id', auth()->id())->get();
        }

        $cursos = \App\Models\Curso::where('estado', 'Activo')->get(); // Only get active courses

        // Add the following lines
        $tiposCursos = \App\Models\TipoCurso::all();
        $cursosPorTipo = $cursos->groupBy('tipo_curso_id');
        $cursoSeleccionado = $matricula->curso_id;
        $tipoCursoSeleccionado = $cursoSeleccionado ? \App\Models\Curso::find($cursoSeleccionado)->tipo_curso_id : null;
        $universidades = \App\Models\Universidad::all();

        return view('matriculas.edit', compact(
            'matricula',
            'usuarios',
            'cursos',
            'tiposCursos',
            'cursosPorTipo',
            'cursoSeleccionado',
            'tipoCursoSeleccionado',
            'universidades'
        ));
    }

    public function update(Request $request, Matricula $matricula)
    {
        $request->validate([
            'usuario_id' => 'required|exists:users,id',
            'curso_id' => 'required|exists:cursos,id',
            'fecha_matricula' => 'required|date',
            'monto_total' => 'required|numeric',
            'valor_pendiente' => 'nullable|numeric',
            'estado_matricula' => 'required|in:Pendiente,Aprobada,Completada,Rechazada',
        ]);

        if (!auth()->user()->hasRole(1) && $matricula->usuario_id != auth()->id()) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para actualizar esta matrícula.');
        }

        $matricula->update($request->all());

        return redirect()->route('matriculas.index')->with('success', 'Matricula actualizada exitosamente.');
    }

    public function destroy(Matricula $matricula)
    {
        if (!auth()->user()->hasRole(1) && $matricula->usuario_id != auth()->id()) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para eliminar esta matrícula.');
        }

        try {
            // Delete associated payments
            $matricula->pagos()->delete();

            // Delete the matricula
            $matricula->delete();

            return redirect()->route('matriculas.index')->with('success', 'Matricula eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('matriculas.index')->with('error', 'Error al eliminar la matrícula: ' . $e->getMessage());
        }
    }

    public function aprobar(Matricula $matricula)
    {
        if (!auth()->user()->hasRole(1)) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para aprobar esta matrícula.');
        }

        $matricula->update(['estado_matricula' => 'Aprobada']);

        // Ensure the usuario relationship is loaded
        $matricula->load('usuario');

        // Send email to the user
        Mail::to($matricula->usuario->email)->send(new MatriculaAprobada($matricula));

        return redirect()->route('matriculas.index')->with('success', 'Matricula aprobada exitosamente.');
    }

    public function rechazar(Matricula $matricula)
    {
        if (!auth()->user()->hasRole(1)) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para rechazar esta matrícula.');
        }

        $matricula->update(['estado_matricula' => 'Rechazada']);

        return redirect()->route('matriculas.index')->with('success', 'Matricula rechazada exitosamente.');
    }

    public function listas(Request $request)
    {
        $tiposCursos = TipoCurso::all();
        $tipoCursoId = $request->query('tipo_curso');
        $cursoId = $request->query('curso_id');
        $perPage = $request->query('per_page', 50); // Valor por defecto: 50

        // Validar que perPage sea uno de los valores permitidos
        $perPage = in_array($perPage, [50, 100, 200]) ? $perPage : 50;

        // Optimizar la consulta de cursos
        $cursos = Curso::when($tipoCursoId, function($query) use ($tipoCursoId) {
            return $query->where('tipo_curso_id', $tipoCursoId);
        })->get();

        // Optimizar la consulta de matrículas con eager loading
        $matriculas = Matricula::with([
            'usuario' => function($query) {
                $query->select('id', 'name', 'email');
            },
            'usuario.profile' => function($query) {
                $query->select('id', 'user_id', 'photo', 'phone', 'carnet');
            }
        ])
        ->when($cursoId, function($query) use ($cursoId) {
            return $query->where('curso_id', $cursoId);
        })
        ->when($tipoCursoId && !$cursoId, function($query) use ($cursos) {
            return $query->whereIn('curso_id', $cursos->pluck('id'));
        })
        ->select('id', 'usuario_id', 'curso_id', 'valor_pendiente', 'estado_matricula')
        ->orderBy('id')
        ->paginate($perPage);

        // Generar QRs solo para la página actual
        $qrCodes = [];
        if ($matriculas->count() > 0) {
            $writer = new PngWriter();
            foreach ($matriculas as $matricula) {
                try {
                    $qrCode = EndroidQrCode::create($matricula->usuario->id)
                        ->setSize(200)
                        ->setMargin(10);
                    
                    $result = $writer->write($qrCode);
                    $qrCodes[$matricula->usuario->id] = base64_encode($result->getString());
                } catch (\Exception $e) {
                    \Log::error('Error generando QR: ' . $e->getMessage());
                }
            }
        }

        return view('matriculas.listas', compact('tiposCursos', 'cursos', 'matriculas', 'cursoId', 'tipoCursoId', 'qrCodes', 'perPage'));
    }

    public function exportPdf(Request $request)
    {
        $cursoId = $request->input('curso_id');
        $curso = \App\Models\Curso::findOrFail($cursoId);

        $matriculas = Matricula::where('curso_id', $cursoId)
                               ->with('usuario')
                               ->get()
                               ->sortBy(function($matricula) {
                                   return $matricula->usuario->name;
                               });

        $pdf = PDF::loadView('matriculas.pdf', compact('curso', 'matriculas'));

        return $pdf->download('listas_matriculados_' . $curso->nombre . '_' . $curso->horario . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $cursoId = $request->input('curso_id');
        $curso = \App\Models\Curso::findOrFail($cursoId);

        $matriculas = Matricula::where('curso_id', $cursoId)
                               ->with('usuario')
                               ->get()
                               ->sortBy(function($matricula) {
                                   return $matricula->usuario->name;
                               });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Nombre del Matriculado');

        foreach ($matriculas as $index => $matricula) {
            $sheet->setCellValue('A' . ($index + 2), $index + 1);
            $sheet->setCellValue('B' . ($index + 2), $matricula->usuario->name);
        }

        // Ajustar el ancho de las columnas
        foreach(range('A','B') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        // Sanitizar el nombre del archivo
        $fileName = 'listas_matriculados_' . preg_replace('/[^a-zA-Z0-9]/', '_', $curso->nombre) . '.xlsx';
        
        // Crear una respuesta directa
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function exportPendientesExcel(Request $request)
    {
        $cursoId = $request->input('curso_id');
        $curso = \App\Models\Curso::findOrFail($cursoId);

        $matriculas = Matricula::where('curso_id', $cursoId)
                               ->where('valor_pendiente', '>', 0)
                               ->with(['usuario' => function($query) {
                                   $query->with('profile');
                               }])
                               ->get()
                               ->sortBy(function($matricula) {
                                   return $matricula->usuario->name;
                               });

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Establecer encabezados
        $sheet->setCellValue('A1', '#');
        $sheet->setCellValue('B1', 'Nombre del Matriculado');
        $sheet->setCellValue('C1', 'Valor Pendiente');
        $sheet->setCellValue('D1', 'Celular');

        // Llenar datos
        foreach ($matriculas as $index => $matricula) {
            $rowIndex = $index + 2;
            $sheet->setCellValue('A' . $rowIndex, $index + 1);
            $sheet->setCellValue('B' . $rowIndex, $matricula->usuario->name);
            $sheet->setCellValue('C' . $rowIndex, $matricula->valor_pendiente);
            $sheet->setCellValue('D' . $rowIndex, $matricula->usuario->profile->phone ?? 'N/A');
        }

        // Ajustar el ancho de las columnas
        foreach(range('A','D') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        
        // Sanitizar el nombre del archivo
        $fileName = 'pendientes_' . preg_replace('/[^a-zA-Z0-9]/', '_', $curso->nombre) . '.xlsx';
        
        // Crear una respuesta directa
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $fileName . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }

    public function printCredentials(Request $request)
    {
        try {
            $ids = explode(',', $request->query('ids'));
            $matriculas = Matricula::whereIn('id', $ids)
                ->with(['usuario', 'usuario.profile', 'curso'])
                ->get()
                ->sortBy(function($matricula) {
                    return $matricula->usuario->name;
                });

            if ($matriculas->isEmpty()) {
                return back()->with('error', 'No se encontraron matrículas para imprimir.');
            }

            // Generar QRs
            $qrCodes = [];
            $writer = new PngWriter();
            foreach ($matriculas as $matricula) {
                try {
                    $qrCode = EndroidQrCode::create($matricula->usuario->id)
                        ->setSize(200)
                        ->setMargin(10);
                    
                    $result = $writer->write($qrCode);
                    $qrCodes[$matricula->usuario->id] = base64_encode($result->getString());
                } catch (\Exception $e) {
                    \Log::error('Error generando QR: ' . $e->getMessage());
                }
            }

            // Actualizar estados
            foreach ($matriculas as $matricula) {
                $matricula->update(['estado_matricula' => 'Entregado']);
                
                if ($matricula->usuario->profile) {
                    $matricula->usuario->profile->update([
                        'carnet' => 'Entregado'
                    ]);
                }
            }

            $pdf = PDF::loadView('matriculas.credentials', [
                'matriculas' => $matriculas,
                'curso' => $matriculas->first()->curso,
                'qrCodes' => $qrCodes
            ]);

            return $pdf->stream('credenciales_matriculados.pdf');

        } catch (\Exception $e) {
            \Log::error('Error al generar credenciales: ' . $e->getMessage());
            return back()->with('error', 'Error al generar las credenciales: ' . $e->getMessage());
        }
    }

    public function uploadBackground(Request $request)
    {
        $request->validate([
            'background' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fixedFileName = 'background.jpg';
        $storagePath = 'public/imagenes_de_fondo_permanentes';
        
        // Delete existing background file if it exists
        if (Storage::exists($storagePath . '/' . $fixedFileName)) {
            Storage::delete($storagePath . '/' . $fixedFileName);
        }

        // Store the new image with the fixed filename
        $request->file('background')->storeAs($storagePath, $fixedFileName);

        // No need to store in session anymore since we'll use a fixed path
        return redirect()->route('matriculas.listas')->with('success', 'Fondo actualizado correctamente.');
    }

    public function calificaciones(Matricula $matricula)
    {
        if (!auth()->user()->hasRole(1) && $matricula->usuario_id != auth()->id()) {
            return redirect()->route('matriculas.index')
                ->with('error', 'No tienes permiso para ver estas calificaciones.');
        }

        // Obtener los intentos de cuestionarios del usuario para el curso de la matrícula
        $intentos = IntentoCuestionario::whereHas('cuestionario.aulaVirtual', function($query) use ($matricula) {
                $query->whereHas('cursos', function($q) use ($matricula) {
                    $q->where('cursos.id', $matricula->curso_id);
                });
            })
            ->where('usuario_id', $matricula->usuario_id)
            ->whereNotNull('calificacion')
            ->with(['cuestionario' => function($query) {
                $query->with('aulaVirtual');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calcular estadísticas
        $estadisticas = [
            'promedio' => $intentos->isNotEmpty() ? $intentos->avg('calificacion') : 0,
            'mejor_nota' => $intentos->isNotEmpty() ? $intentos->max('calificacion') : 0,
            'peor_nota' => $intentos->isNotEmpty() ? $intentos->min('calificacion') : 0,
            'total_cuestionarios' => $intentos->groupBy('cuestionario_id')->count(),
            'cuestionarios_completados' => $intentos->count(),
        ];

        return view('matriculas.calificaciones', compact('matricula', 'intentos', 'estadisticas'));
    }
}