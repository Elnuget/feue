<?php

namespace App\Http\Controllers;

use App\Models\Universidad;
use Illuminate\Http\Request;

class UniversidadController extends Controller
{
    public function index()
    {
        $universidades = Universidad::all();
        return view('universidades.index', compact('universidades'));
    }

    public function create()
    {
        return view('universidades.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:universidades',
        ]);

        Universidad::create($request->all());
        return redirect()->route('universidades.index');
    }

    public function edit(Universidad $universidad)
    {
        return view('universidades.edit', compact('universidad'));
    }

    public function update(Request $request, Universidad $universidad)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:universidades,nombre,' . $universidad->id,
        ]);

        $universidad->update($request->all());
        return redirect()->route('universidades.index');
    }

    public function destroy(Universidad $universidad)
    {
        // Check for dependencies in user_aspiraciones before deleting
        if ($universidad->userAspiraciones()->count() > 0) {
            return redirect()->route('universidades.index')->withErrors('No se puede eliminar la universidad porque tiene dependencias.');
        }

        $universidad->delete();
        return redirect()->route('universidades.index');
    }
}