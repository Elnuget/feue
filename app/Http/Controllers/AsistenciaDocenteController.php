<?php

namespace App\Http\Controllers;

use App\Models\AsistenciaDocente;
use App\Models\SesionDocente;
use App\Models\User;
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

    public function index(Request $request)
    {
        $mes = $request->get('mes', now()->format('Y-m'));
        
        $fechaInicio = \Carbon\Carbon::createFromFormat('Y-m', $mes)->startOfMonth();
        $fechaFin = \Carbon\Carbon::createFromFormat('Y-m', $mes)->endOfMonth();
        
        $asistencias = AsistenciaDocente::with(['docente', 'sesion'])
            ->whereBetween('fecha', [$fechaInicio, $fechaFin])
            ->orderBy('fecha', 'desc')
            ->get();
            
        $docentes = User::whereHas('roles', function($query) {
            $query->where('name', 'Docente');
        })->get();

        return view('asistencias_docentes.index', compact('asistencias', 'docentes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
            'estado' => 'required|in:Presente,Tarde,Ausente',
            'observaciones' => 'nullable|string|max:500'
        ]);

        // Combinar fecha con hora_entrada usando la zona horaria de Guayaquil
        $fechaHoraEntrada = Carbon::parse($request->fecha . ' ' . $request->hora_entrada, 'America/Guayaquil');

        // Buscar si existe una sesión docente para esta fecha y hora
        $sesion = SesionDocente::where('user_id', $request->user_id)
            ->whereDate('fecha', $request->fecha)
            ->where('hora_inicio', '<=', $fechaHoraEntrada)
            ->first();

        $asistencia = AsistenciaDocente::create([
            'user_id' => $request->user_id,
            'fecha' => $request->fecha,
            'hora_entrada' => $fechaHoraEntrada,
            'estado' => $request->estado,
            'sesion_docente_id' => $sesion ? $sesion->id : null,
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('asistencias-docentes.index')
            ->with('success', 'Asistencia registrada correctamente');
    }

    public function show(AsistenciaDocente $asistencia)
    {
        return view('asistencias.show', compact('asistencia'));
    }

    public function edit(AsistenciaDocente $asistenciaDocente)
    {
        try {
            \Log::info('Buscando asistencia con ID: ' . $asistenciaDocente->id);
            
            // Verificar si el modelo existe
            if (!$asistenciaDocente->exists) {
                \Log::error('Asistencia no encontrada con ID: ' . $asistenciaDocente->id);
                return response()->json([
                    'error' => 'Asistencia no encontrada'
                ], 404);
            }

            // Cargar la relación docente
            $asistenciaDocente->load('docente');

            if (!$asistenciaDocente->docente) {
                \Log::error('Docente no encontrado para asistencia ID: ' . $asistenciaDocente->id);
                return response()->json([
                    'error' => 'No se encontró el docente asociado'
                ], 404);
            }

            $data = [
                'id' => $asistenciaDocente->id,
                'docente' => [
                    'name' => $asistenciaDocente->docente->name
                ],
                'fecha' => $asistenciaDocente->fecha->format('Y-m-d'),
                'hora_entrada' => $asistenciaDocente->hora_entrada->format('H:i'),
                'estado' => $asistenciaDocente->estado,
                'observaciones' => $asistenciaDocente->observaciones ?? ''
            ];

            \Log::info('Datos de asistencia recuperados:', $data);

            return response()->json($data);

        } catch (\Exception $e) {
            \Log::error('Error en edit: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return response()->json([
                'error' => 'Error al cargar los datos: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, AsistenciaDocente $asistenciaDocente)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora_entrada' => 'required',
            'estado' => 'required|in:Presente,Tarde,Ausente',
            'observaciones' => 'nullable|string|max:500'
        ]);

        $fechaHoraEntrada = Carbon::parse($request->fecha . ' ' . $request->hora_entrada, 'America/Guayaquil');

        $asistenciaDocente->update([
            'fecha' => $request->fecha,
            'hora_entrada' => $fechaHoraEntrada,
            'estado' => $request->estado,
            'observaciones' => $request->observaciones
        ]);

        return redirect()->route('asistencias-docentes.index')
            ->with('success', 'Asistencia actualizada correctamente');
    }

    public function destroy(AsistenciaDocente $asistenciaDocente)
    {
        $asistenciaDocente->delete();

        return redirect()->route('asistencias-docentes.index')
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