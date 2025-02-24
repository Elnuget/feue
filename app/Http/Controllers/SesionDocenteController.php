<?php

namespace App\Http\Controllers;

use App\Models\SesionDocente;
use App\Models\Curso;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SesionDocenteController extends Controller
{
    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->format('Y-m'));
        
        $fechaInicio = \Carbon\Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
        $fechaFin = \Carbon\Carbon::createFromFormat('Y-m', $mes)->endOfMonth();
        
        $sesiones = SesionDocente::with(['docente', 'curso.tipoCurso'])
            ->when(Auth::user()->hasRole('Docente'), function ($query) {
                return $query->where('user_id', Auth::id());
            })
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->orderBy('hora_inicio', 'asc')
            ->get();

        $docentes = User::role('Docente')->get();
        $cursos = Curso::with('tipoCurso')
            ->orderBy('id', 'desc')
            ->get();

        return view('sesiones_docentes.index', compact('sesiones', 'docentes', 'cursos'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('id', 'desc')->get();
        $docentes = User::role('Docente')->get();
        return view('sesiones_docentes.create', compact('cursos', 'docentes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'aula' => 'nullable|string|max:100',
            'materia' => 'nullable|string|max:150',
            'tema_impartido' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);

        $validated['user_id'] = Auth::user()->hasRole('Docente') ? Auth::id() : $request->user_id;

        SesionDocente::create($validated);

        return redirect()->route('sesiones-docentes.index')
            ->with('success', 'Sesión registrada exitosamente.');
    }

    public function edit(SesionDocente $sesionesDocente)
    {
        $cursos = Curso::all();
        $docentes = User::role('Docente')->get();
        return view('sesiones_docentes.edit', compact('sesionesDocente', 'cursos', 'docentes'));
    }

    public function update(Request $request, SesionDocente $sesionesDocente)
    {
        $validated = $request->validate([
            'curso_id' => 'required|exists:cursos,id',
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'aula' => 'nullable|string|max:100',
            'materia' => 'nullable|string|max:150',
            'tema_impartido' => 'nullable|string',
            'observaciones' => 'nullable|string'
        ]);

        if (!Auth::user()->hasRole('Docente')) {
            $validated['user_id'] = $request->user_id;
        }

        $sesionesDocente->update($validated);

        return redirect()->route('sesiones-docentes.index')
            ->with('success', 'Sesión actualizada exitosamente.');
    }

    public function destroy(SesionDocente $sesionesDocente)
    {
        $sesionesDocente->delete();

        return redirect()->route('sesiones-docentes.index')
            ->with('deleted', 'Sesión eliminada exitosamente.');
    }
}