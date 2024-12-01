<?php

namespace App\Http\Controllers;

use App\Models\Matricula;
use Illuminate\Http\Request;

class MatriculaController extends Controller
{
    public function index()
    {
        $matriculas = Matricula::all();
        return view('matriculas.index', compact('matriculas'));
    }

    public function create()
    {
        return view('matriculas.create');
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
        ]);

        Matricula::create($request->all());

        return redirect()->route('matriculas.index')->with('success', 'Matricula creada exitosamente.');
    }

    public function show(Matricula $matricula)
    {
        return view('matriculas.show', compact('matricula'));
    }

    public function edit(Matricula $matricula)
    {
        return view('matriculas.edit', compact('matricula'));
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

        $matricula->update($request->all());

        return redirect()->route('matriculas.index')->with('success', 'Matricula actualizada exitosamente.');
    }

    public function destroy(Matricula $matricula)
    {
        $matricula->delete();

        return redirect()->route('matriculas.index')->with('success', 'Matricula eliminada exitosamente.');
    }
}