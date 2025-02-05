<?php

namespace App\Http\Controllers;

use App\Models\AulaVirtual;
use App\Models\Curso;
use Illuminate\Http\Request;

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
}
