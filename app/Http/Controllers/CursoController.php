<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\TipoCurso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index()
    {
        $cursos = Curso::with('tipoCurso')->get();
        return view('cursos.index', compact('cursos'));
    }

    public function create()
    {
        $tiposCursos = TipoCurso::all();
        return view('cursos.create', compact('tiposCursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'estado' => 'required|in:Activo,Inactivo',
            'tipo_curso_id' => 'required|exists:tipos_cursos,id',
        ]);

        Curso::create($request->all());
        return redirect()->route('cursos.index');
    }

    public function edit(Curso $curso)
    {
        $tiposCursos = TipoCurso::all();
        return view('cursos.edit', compact('curso', 'tiposCursos'));
    }

    public function update(Request $request, Curso $curso)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'precio' => 'required|numeric',
            'estado' => 'required|in:Activo,Inactivo',
            'tipo_curso_id' => 'required|exists:tipos_cursos,id',
        ]);

        $curso->update($request->all());
        return redirect()->route('cursos.index');
    }

    public function destroy(Curso $curso)
    {
        // Check for dependencies in matriculas before deleting
        if ($curso->matriculas()->count() > 0) {
            return redirect()->route('cursos.index')->withErrors('No se puede eliminar el curso porque tiene dependencias.');
        }

        $curso->delete();
        return redirect()->route('cursos.index');
    }
}