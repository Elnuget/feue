<?php

namespace App\Http\Controllers;

use App\Models\AcuerdoConfidencialidad;
use App\Models\Curso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AcuerdoConfidencialidadController extends Controller
{
    public function index()
    {
        $acuerdos = AcuerdoConfidencialidad::with(['user', 'curso'])->get();
        return view('acuerdos-confidencialidad.index', compact('acuerdos'));
    }

    public function create()
    {
        $cursos = Curso::all();
        return view('acuerdos-confidencialidad.create', compact('cursos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'acuerdo' => 'required|file|mimes:pdf|max:10240',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        $archivo = $request->file('acuerdo');
        $ruta = $archivo->store('acuerdos-confidencialidad', 'public');

        AcuerdoConfidencialidad::create([
            'estado' => 'Pendiente',
            'acuerdo' => $ruta,
            'curso_id' => $request->curso_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('acuerdos-confidencialidad.index')
            ->with('success', 'Acuerdo de confidencialidad creado exitosamente.');
    }

    public function show(AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        return view('acuerdos-confidencialidad.show', compact('acuerdoConfidencialidad'));
    }

    public function edit(AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        $cursos = Curso::all();
        return view('acuerdos-confidencialidad.edit', compact('acuerdoConfidencialidad', 'cursos'));
    }

    public function update(Request $request, AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        $request->validate([
            'estado' => 'required|in:Pendiente,Entregado',
            'curso_id' => 'required|exists:cursos,id',
        ]);

        if ($request->hasFile('acuerdo')) {
            $request->validate([
                'acuerdo' => 'required|file|mimes:pdf|max:10240',
            ]);

            // Eliminar archivo anterior
            if ($acuerdoConfidencialidad->acuerdo) {
                Storage::disk('public')->delete($acuerdoConfidencialidad->acuerdo);
            }

            $archivo = $request->file('acuerdo');
            $ruta = $archivo->store('acuerdos-confidencialidad', 'public');
            $acuerdoConfidencialidad->acuerdo = $ruta;
        }

        $acuerdoConfidencialidad->estado = $request->estado;
        $acuerdoConfidencialidad->curso_id = $request->curso_id;
        $acuerdoConfidencialidad->save();

        return redirect()->route('acuerdos-confidencialidad.index')
            ->with('success', 'Acuerdo de confidencialidad actualizado exitosamente.');
    }

    public function destroy(AcuerdoConfidencialidad $acuerdoConfidencialidad)
    {
        if ($acuerdoConfidencialidad->acuerdo) {
            Storage::disk('public')->delete($acuerdoConfidencialidad->acuerdo);
        }

        $acuerdoConfidencialidad->delete();

        return redirect()->route('acuerdos-confidencialidad.index')
            ->with('success', 'Acuerdo de confidencialidad eliminado exitosamente.');
    }
    
    /**
     * Verifica si un usuario tiene acuerdos de confidencialidad
     * 
     * @param int $userId ID del usuario
     * @return bool
     */
    public static function tieneAcuerdoConfidencialidad($userId)
    {
        return AcuerdoConfidencialidad::where('user_id', $userId)->exists();
    }
} 