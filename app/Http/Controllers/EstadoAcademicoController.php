<?php

namespace App\Http\Controllers;

use App\Models\EstadoAcademico;
use Illuminate\Http\Request;

class EstadoAcademicoController extends Controller
{
    public function index()
    {
        $estados = EstadoAcademico::all();
        return view('estados_academicos.index', compact('estados'));
    }

    public function create()
    {
        return view('estados_academicos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:estados_academicos',
        ]);

        EstadoAcademico::create($request->all());
        return redirect()->route('estados_academicos.index');
    }

    public function edit(EstadoAcademico $estado_academico)
    {
        return view('estados_academicos.edit', compact('estado_academico'));
    }

    public function update(Request $request, EstadoAcademico $estado_academico)
    {
        $request->validate([
            'nombre' => 'required|string|max:50|unique:estados_academicos,nombre,' . $estado_academico->id,
        ]);

        $estado_academico->update($request->all());
        return redirect()->route('estados_academicos.index');
    }

    public function destroy(EstadoAcademico $estado_academico)
    {
        $estado_academico->delete();
        return redirect()->route('estados_academicos.index');
    }
}