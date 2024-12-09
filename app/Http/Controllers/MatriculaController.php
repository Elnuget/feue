<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

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
        return view('matriculas.show', compact('matricula'));
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
}