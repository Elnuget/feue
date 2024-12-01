<?php

namespace App\Http\Controllers;

use App\Models\UserAcademico;
use Illuminate\Http\Request;

class UserAcademicoController extends Controller
{
    public function index()
    {
        $userAcademicos = UserAcademico::with('user', 'estadoAcademico')->get();
        return view('user_academicos.index', compact('userAcademicos'));
    }

    public function create()
    {
        return view('user_academicos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'estado_academico_id' => 'required|exists:estados_academicos,id',
            'acta_grado' => 'nullable|string|max:255',
        ]);

        UserAcademico::create($request->all());

        return redirect()->route('user_academicos.index');
    }

    public function show(UserAcademico $userAcademico)
    {
        return view('user_academicos.show', compact('userAcademico'));
    }

    public function edit(UserAcademico $userAcademico)
    {
        return view('user_academicos.edit', compact('userAcademico'));
    }

    public function update(Request $request, UserAcademico $userAcademico)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'estado_academico_id' => 'required|exists:estados_academicos,id',
            'acta_grado' => 'nullable|string|max:255',
        ]);

        $userAcademico->update($request->all());

        return redirect()->route('user_academicos.index');
    }

    public function destroy(UserAcademico $userAcademico)
    {
        $userAcademico->delete();

        return redirect()->route('user_academicos.index');
    }
}