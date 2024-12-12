<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('user')->get();
        return view('asistencias.index', compact('asistencias'));
    }

    public function create()
    {
        $users = User::all();
        return view('asistencias.create', compact('users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_hora' => 'required|date',
        ]);

        Asistencia::create($validated);
        return redirect()->route('asistencias.index')->with('success', 'Asistencia registrada exitosamente.');
    }

    public function edit(Asistencia $asistencia)
    {
        $users = User::all();
        return view('asistencias.edit', compact('asistencia', 'users'));
    }

    public function update(Request $request, Asistencia $asistencia)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha_hora' => 'required|date',
        ]);

        $asistencia->update($validated);
        return redirect()->route('asistencias.index')->with('success', 'Asistencia actualizada exitosamente.');
    }

    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();
        return redirect()->route('asistencias.index')->with('success', 'Asistencia eliminada exitosamente.');
    }

    public function scanQR()
    {
        return view('asistencias.scan');
    }

    public function registerScan(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        Asistencia::create([
            'user_id' => $validated['user_id'],
            'fecha_hora' => now()->setTimezone('America/Guayaquil')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Asistencia registrada exitosamente'
        ]);
    }

    public function show(Asistencia $asistencia)
    {
        return view('asistencias.show', compact('asistencia'));
    }
}