<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaDocente;
use App\Models\SesionDocente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AsistenciaDocenteController extends Controller
{
    public function __construct()
    {
        // Establecer la zona horaria para todas las operaciones de este controlador
        Carbon::setLocale('es');
        date_default_timezone_set('America/Guayaquil');
    }

    public function index()
    {
        $asistencias = AsistenciaDocente::with(['docente', 'sesion'])
            ->orderBy('id', 'desc')
            ->paginate(10);

        return view('asistencias.index', compact('asistencias'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
            'estado' => 'required|in:Presente,Tarde,Ausente',
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Combinar fecha con hora_entrada usando la zona horaria de Guayaquil
        $fechaHoraEntrada = Carbon::parse($request->fecha . ' ' . $request->hora_entrada, 'America/Guayaquil');

        // Buscar si existe una sesión docente para esta fecha y hora
        $sesion = SesionDocente::where('user_id', Auth::id())
            ->whereDate('fecha', $request->fecha)
            ->where('hora_inicio', '<=', $fechaHoraEntrada)
            ->first();

        $asistencia = AsistenciaDocente::create([
            'user_id' => Auth::id(),
            'fecha' => $request->fecha,
            'hora_entrada' => $fechaHoraEntrada,
            'estado' => $request->estado,
            'sesion_docente_id' => $sesion ? $sesion->id : null,
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia registrada correctamente');
    }

    public function show(AsistenciaDocente $asistencia)
    {
        return view('asistencias.show', compact('asistencia'));
    }

    public function edit(AsistenciaDocente $asistencia)
    {
        $sesiones = SesionDocente::where('user_id', $asistencia->user_id)
            ->whereDate('fecha', $asistencia->fecha)
            ->get();

        return view('asistencias.edit', compact('asistencia', 'sesiones'));
    }

    public function update(Request $request, AsistenciaDocente $asistencia)
    {
        $request->validate([
            'estado' => 'required|in:Presente,Tarde,Ausente',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $asistencia->update($request->only(['estado', 'observaciones']));

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia actualizada correctamente');
    }

    public function destroy(AsistenciaDocente $asistencia)
    {
        $asistencia->delete();

        return redirect()->route('asistencias.index')
            ->with('success', 'Asistencia eliminada correctamente');
    }

    public function reporteMensual(Request $request)
    {
        $mes = $request->get('mes', Carbon::now()->format('Y-m'));
        
        $asistencias = AsistenciaDocente::with(['docente'])
            ->whereYear('fecha', Carbon::parse($mes)->year)
            ->whereMonth('fecha', Carbon::parse($mes)->month)
            ->get()
            ->groupBy('user_id');

        return view('asistencias.reporte-mensual', compact('asistencias', 'mes'));
    }
} 