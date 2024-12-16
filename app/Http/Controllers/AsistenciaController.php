<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use App\Models\Matricula;
use App\Models\Curso;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index()
    {
        $asistencias = Asistencia::with('user')->get();
        $listas = Matricula::with('usuario')->get();
        $cursos = Curso::all();
        return view('asistencias.index', compact('asistencias', 'listas', 'cursos'));
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
        $data = $request->input('data');

        // Procesar el QR escaneado y registrar la asistencia
        $userId = $this->decodeQRCodeData($data);
        if ($userId) {
            Asistencia::create([
                'user_id' => $userId,
                'fecha_hora' => now(),
            ]);
            return redirect()->route('asistencias.index')->with('success', 'Asistencia registrada correctamente.');
        } else {
            return redirect()->back()->with('error', 'Datos de QR inválidos.');
        }
    }

    private function decodeQRCodeData($data)
    {
        // Implementa la lógica para decodificar los datos del QR
        // Por ejemplo, si el QR contiene el ID del usuario:
        return intval($data);
    }

    public function show(Asistencia $asistencia)
    {
        return view('asistencias.show', compact('asistencia'));
    }
}