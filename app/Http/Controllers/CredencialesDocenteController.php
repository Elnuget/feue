<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SesionDocente;
use App\Models\AsistenciaDocente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CredencialesDocenteController extends Controller
{
    /**
     * Mostrar lista de credenciales de docentes
     */
    public function index()
    {
        // Obtener todos los usuarios con rol de docente
        $docentes = User::whereHas('roles', function($query) {
            $query->where('name', 'Docente');
        })->get();

        // Para cada docente, obtener información adicional
        foreach ($docentes as $docente) {
            // Número total de sesiones impartidas
            $docente->total_sesiones = SesionDocente::where('user_id', $docente->id)->count();
            
            // Número total de asistencias registradas
            $docente->total_asistencias = AsistenciaDocente::where('user_id', $docente->id)->count();
            
            // Porcentaje de asistencia
            if ($docente->total_sesiones > 0) {
                $docente->porcentaje_asistencia = round(($docente->total_asistencias / $docente->total_sesiones) * 100, 2);
            } else {
                $docente->porcentaje_asistencia = 0;
            }
            
            // Última sesión impartida
            $docente->ultima_sesion = SesionDocente::where('user_id', $docente->id)
                ->orderBy('fecha', 'desc')
                ->first();
        }

        return view('credenciales-docentes.index', compact('docentes'));
    }

    /**
     * Mostrar detalles de un docente específico
     */
    public function show($id)
    {
        $docente = User::findOrFail($id);
        
        // Verificar que sea un docente
        if (!$docente->hasRole('Docente')) {
            return redirect()->route('credenciales-docentes.index')
                ->with('error', 'El usuario seleccionado no es un docente.');
        }
        
        // Obtener todas las sesiones del docente
        $sesiones = SesionDocente::where('user_id', $id)
            ->orderBy('fecha', 'desc')
            ->get();
            
        // Obtener todas las asistencias del docente
        $asistencias = AsistenciaDocente::where('user_id', $id)
            ->orderBy('fecha', 'desc')
            ->get();
        
        return view('credenciales-docentes.show', compact('docente', 'sesiones', 'asistencias'));
    }
}
