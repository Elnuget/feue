<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use App\Models\Curso;    // Add this line
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
    public function index()
    {
        if (auth()->user()->hasRole(1)) {
            $matriculas = Matricula::all();
        } else {
            $matriculas = Matricula::where('usuario_id', auth()->id())->get();
        }
        return view('matriculas.index', compact('matriculas'));
    }

    public function create(Request $request)
    {
        if (auth()->user()->hasRole(1)) {
            $usuarios = \App\Models\User::all();
        } else {
            $usuarios = \App\Models\User::where('id', auth()->id())->get();
        }
        $cursos = \App\Models\Curso::all();
        $cursoSeleccionado = $request->input('curso_id');
        $universidades = \App\Models\Universidad::all();
        return view('matriculas.create', compact('usuarios', 'cursos', 'cursoSeleccionado', 'universidades'));
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
        $cursos = Curso::all();
        $cursoId = $request->query('curso_id');
        
        $matriculas = collect();
        if ($cursoId) {
            $matriculas = Matricula::where('curso_id', $cursoId)
                ->with('usuario')
                ->get();
        }

        return view('matriculas.listas', compact('cursos', 'matriculas', 'cursoId'));
    }

    public function exportPdf(Request $request)
    {
        $cursoId = $request->input('curso_id');
        $curso = \App\Models\Curso::findOrFail($cursoId);

        $matriculas = Matricula::where('curso_id', $cursoId)
                               ->where('estado_matricula', 'Aprobada')
                               ->with('usuario')
                               ->get()
                               ->sortBy(function($matricula) {
                                   return $matricula->usuario->name;
                               });

        $pdf = PDF::loadView('matriculas.pdf', compact('curso', 'matriculas'));

        return $pdf->download('listas_matriculados_' . $curso->nombre . '.pdf');
    }

    public function exportExcel(Request $request)
    {
        $cursoId = $request->input('curso_id');
        $curso = \App\Models\Curso::findOrFail($cursoId);

        $matriculas = Matricula::where('curso_id', $cursoId)
                               ->where('estado_matricula', 'Aprobada')
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
        $fileName = 'listas_matriculados_' . $curso->nombre . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }
}