<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\TipoCurso;
use Illuminate\Http\Request;

class CursoController extends Controller
{
    public function index(Request $request)
    {
        $query = Curso::with('tipoCurso');

        // Filtro por estado
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        // Filtro por tipo de curso
        if ($request->filled('tipo_curso_id')) {
            $query->where('tipo_curso_id', $request->tipo_curso_id);
        }

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('nombre', 'LIKE', '%' . $request->search . '%');
        }

        // Ordenar por fecha de creación
        $cursos = $query->orderBy('created_at', 'desc')->get();
        $tiposCursos = TipoCurso::all();

        return view('cursos.index', compact('cursos', 'tiposCursos'));
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
            'imagen' => 'nullable|image|mimes:jpeg,png|max:2048',
            'horario' => 'nullable|string|max:255',
            'horas' => 'nullable|integer|min:1',
        ]);

        $data = $request->except(['imagen']);

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('cursos', 'public');
        }

        Curso::create($data);
        return redirect()->route('cursos.index')->with('success', 'Curso creado correctamente');
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
            'imagen' => 'nullable|image|mimes:jpeg,png|max:2048',
            'horario' => 'nullable|string|max:255',
            'horas' => 'nullable|integer|min:1',
        ]);

        $data = $request->except(['imagen']);

        if ($request->hasFile('imagen')) {
            if ($curso->imagen) {
                \Storage::disk('public')->delete($curso->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('cursos', 'public');
        }

        $curso->update($data);
        return redirect()->route('cursos.index')->with('updated', 'Curso actualizado correctamente');
    }

    public function destroy(Curso $curso)
    {
        // Check for dependencies in matriculas before deleting
        if ($curso->matriculas()->count() > 0) {
            return redirect()->route('cursos.index')->withErrors('No se puede eliminar el curso porque tiene dependencias.');
        }

        if ($curso->imagen) {
            \Storage::disk('public')->delete($curso->imagen);
        }

        $curso->delete();
        return redirect()->route('cursos.index')->with('deleted', 'Curso eliminado correctamente');
    }

    public function disable(Curso $curso)
    {
        $curso->update(['estado' => 'Inactivo']);
        return redirect()->route('cursos.index')->with('success', 'Curso deshabilitado correctamente');
    }

    public function enable(Curso $curso)
    {
        $curso->update(['estado' => 'Activo']);
        return redirect()->route('cursos.index')->with('success', 'Curso habilitado correctamente');
    }

    public function disableMultiple(Request $request)
    {
        $cursoIds = $request->input('cursos', []);
        Curso::whereIn('id', $cursoIds)->update(['estado' => 'Inactivo']);
        return redirect()->route('cursos.index')->with('success', 'Cursos deshabilitados correctamente');
    }

    public function dashboard()
    {
        $cursosPorTipo = Curso::with('tipoCurso')->where('estado', 'Activo')->get()->groupBy('tipo_curso_id');
        $tiposCurso = TipoCurso::whereIn('id', $cursosPorTipo->keys())->get();
        
        return view('dashboard', compact('cursosPorTipo', 'tiposCurso'));
    }

    public function welcome()
    {
        $cursosPorTipo = Curso::with('tipoCurso')->where('estado', 'Activo')->get()->groupBy('tipo_curso_id');
        $tiposCurso = TipoCurso::whereIn('id', $cursosPorTipo->keys())->get();
        
        return view('welcome', compact('cursosPorTipo', 'tiposCurso'));
    }
}