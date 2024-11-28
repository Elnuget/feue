<?php

namespace App\Http\Controllers;

use App\Models\TipoCurso;
use Illuminate\Http\Request;

class TipoCursoController extends Controller
{
    public function index()
    {
        $tiposCursos = TipoCurso::all();
        return view('tipos_cursos.index', compact('tiposCursos'));
    }

    public function create()
    {
        return view('tipos_cursos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_cursos',
            'descripcion' => 'nullable|string',
        ]);

        TipoCurso::create($request->all());
        return redirect()->route('tipos_cursos.index');
    }

    public function edit(TipoCurso $tipoCurso)
    {
        return view('tipos_cursos.edit', compact('tipoCurso'));
    }

    public function update(Request $request, TipoCurso $tipoCurso)
    {
        $request->validate([
            'nombre' => 'required|string|max:100|unique:tipos_cursos,nombre,' . $tipoCurso->id,
            'descripcion' => 'nullable|string',
        ]);

        $tipoCurso->update($request->all());
        return redirect()->route('tipos_cursos.index');
    }

    public function destroy(TipoCurso $tipoCurso)
    {
        // Check for dependencies in cursos before deleting
        if ($tipoCurso->cursos()->count() > 0) {
            return redirect()->route('tipos_cursos.index')->withErrors('No se puede eliminar el tipo de curso porque tiene dependencias.');
        }

        $tipoCurso->delete();
        return redirect()->route('tipos_cursos.index');
    }
}