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

        // Get courses based on tipo_curso
        if ($tipoCursoId) {
            $cursos = Curso::where('tipo_curso_id', $tipoCursoId)->get();
            if ($cursoId) {
                $query->where('curso_id', $cursoId);
            } else {
                $query->whereIn('curso_id', $cursos->pluck('id'));
            }
        } else {
            $cursos = collect();
        }

        // Add join with users table and sort by name
        $matriculas = $query->join('users', 'matriculas.usuario_id', '=', 'users.id')
                           ->select('matriculas.*')
                           ->orderBy('users.name')
                           ->get();

        return view('matriculas.index', compact('matriculas', 'tiposCursos', 'cursos', 'tipoCursoId', 'cursoId'));
    }

    public function create(Request $request)
    {
        if (auth()->user()->hasRole(1)) {
            $usuarios = \App\Models\User::all();
        } else {
            $usuarios = \App\Models\User::where('id', auth()->id())->get();
        }
        $cursos = \App\Models\Curso::all();
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

        if (!auth()->user()->hasRole(1) && $request->usuario_id != auth()->id()) {
            return redirect()->route('matriculas.index')->with('error', 'No tienes permiso para crear esta matrícula.');
        }

        $data = $request->all();
        $data['valor_pendiente'] = $data['monto_total'];

        if (!auth()->user()->hasRole(1)) {
            $data['estado_matricula'] = 'Pendiente';
        }

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

        $cursos = \App\Models\Curso::all();

        return view('matriculas.edit', compact('matricula', 'usuarios', 'cursos'));
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

        $matricula->delete();

        return redirect()->route('matriculas.index')->with('success', 'Matricula eliminada exitosamente.');
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

        // Filter courses based on selected tipo_curso
        if ($tipoCursoId) {
            $cursos = Curso::where('tipo_curso_id', $tipoCursoId)->get();
        } else {
            $cursos = collect();
        }

        // Filter matriculas based on selected curso_id or tipo_curso
        if ($cursoId) {
            $matriculas = Matricula::where('curso_id', $cursoId)
                ->with('usuario')
                ->get()
                ->sortBy(function($matricula) {
                    return $matricula->usuario->name;
                });
        } elseif ($tipoCursoId) {
            $cursoIds = Curso::where('tipo_curso_id', $tipoCursoId)->pluck('id');
            $matriculas = Matricula::whereIn('curso_id', $cursoIds)
                ->with('usuario')
                ->get()
                ->sortBy(function($matricula) {
                    return $matricula->usuario->name;
                });
        } else {
            $matriculas = collect();
        }

        return view('matriculas.listas', compact('tiposCursos', 'cursos', 'matriculas', 'cursoId', 'tipoCursoId'));
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

        $writer = new Xlsx($spreadsheet);
        $fileName = 'listas_matriculados_' . $curso->nombre . '_' . $curso->horario . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
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

            $pdf = PDF::loadView('matriculas.credentials', [
                'matriculas' => $matriculas,
                'curso' => $matriculas->first()->curso
            ]);

            return $pdf->download('credenciales_matriculados.pdf');
        } catch (\Exception $e) {
            \Log::error('Error al generar credenciales: ' . $e->getMessage());
            return back()->with('error', 'Error al generar las credenciales.');
        }
    }
}