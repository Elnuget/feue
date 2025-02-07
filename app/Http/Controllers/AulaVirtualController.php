<?php

namespace App\Http\Controllers;

use App\Models\AulaVirtual;
use App\Models\Curso;
use App\Models\AulaVirtualContenido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AulaVirtualController extends Controller
{
    public function index()
    {
        $aulasVirtuales = AulaVirtual::with('cursos')->orderBy('id', 'desc')->get();
        return view('aulas_virtuales.index', compact('aulasVirtuales'));
    }

    public function create()
    {
        $cursos = Curso::orderBy('id', 'desc')->get();
        return view('aulas_virtuales.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cursos' => 'nullable|array',
            'cursos.*' => 'exists:cursos,id'
        ]);

        $aulaVirtual = AulaVirtual::create($validated);
        
        if ($request->has('cursos')) {
            $aulaVirtual->cursos()->sync($request->cursos);
        }

        return redirect()
            ->route('aulas_virtuales.index')
            ->with('success', 'Aula virtual creada exitosamente.');
    }

    public function edit(AulaVirtual $aulasVirtuale)
    {
        $cursos = Curso::orderBy('id', 'desc')->get();
        $aulaVirtual = $aulasVirtuale;
        return view('aulas_virtuales.edit', compact('aulaVirtual', 'cursos'));
    }

    public function update(Request $request, AulaVirtual $aulasVirtuale)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'cursos' => 'nullable|array',
            'cursos.*' => 'exists:cursos,id'
        ]);

        $aulasVirtuale->update($validated);

        if ($request->has('cursos')) {
            $aulasVirtuale->cursos()->sync($request->cursos);
        }

        return redirect()
            ->route('aulas_virtuales.index')
            ->with('success', 'Aula virtual actualizada exitosamente.');
    }

    public function destroy(AulaVirtual $aulasVirtuale)
    {
        $aulasVirtuale->cursos()->detach();
        $aulasVirtuale->delete();

        return redirect()
            ->route('aulas_virtuales.index')
            ->with('success', 'Aula virtual eliminada exitosamente.');
    }

    public function show(AulaVirtual $aulasVirtuale)
    {
        $aula = $aulasVirtuale;
        return view('aulas_virtuales.show', compact('aula'));
    }

    public function storeContenido(Request $request, AulaVirtual $aulasVirtuale)
    {
        if (!auth()->user()->hasRole(1)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $request->validate([
            'titulo' => 'required|string|max:255',
            'contenido' => 'nullable|string',
            'enlace' => 'nullable|url',
            'archivo' => 'nullable|file|max:10240',
        ]);

        $data = $request->only(['titulo', 'contenido', 'enlace']);
        
        if ($request->hasFile('archivo')) {
            $data['archivo'] = $request->file('archivo')->store('aulas_virtuales/archivos', 'public');
        }

        $aulasVirtuale->contenidos()->create($data);

        return redirect()->back()->with('success', 'Contenido agregado exitosamente');
    }

    public function destroyContenido($id)
    {
        if (!auth()->user()->hasRole(1)) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $contenido = AulaVirtualContenido::findOrFail($id);
        
        if ($contenido->archivo) {
            Storage::disk('public')->delete($contenido->archivo);
        }
        
        $contenido->delete();

        return redirect()->back()->with('success', 'Contenido eliminado exitosamente');
    }
}
