<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\SesionDocente;
use App\Models\AsistenciaDocente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

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

    /**
     * Imprimir credenciales de docentes
     */
    public function printCredentials(Request $request)
    {
        $ids = explode(',', $request->input('ids'));
        $docentes = User::whereIn('id', $ids)->get();
        
        // Generar códigos QR para cada docente
        $qrCodes = [];
        foreach ($docentes as $docente) {
            $qrUrl = route('users.qr', $docente->id);
            $qrCodes[$docente->id] = base64_encode(QrCode::size(200)
                ->errorCorrection('H')
                ->margin(1)
                ->encoding('UTF-8')
                ->generate($qrUrl));
            
            // Marcar la credencial como entregada (actualización más explícita)
            if ($docente->profile) {
                $docente->profile->carnet = 'Entregado';
                $docente->profile->save();
            } else {
                // Si no tiene perfil, crear uno
                $profile = new \App\Models\UserProfile([
                    'user_id' => $docente->id,
                    'carnet' => 'Entregado'
                ]);
                $profile->save();
            }
        }
        
        $pdf = PDF::loadView('credenciales-docentes.credentials', compact('docentes', 'qrCodes'));
        $pdf->setPaper([0, 0, 153.014, 242.646]); // Tamaño de tarjeta de crédito en puntos (53.975mm x 85.725mm)
        
        return $pdf->stream('credenciales_docentes.pdf');
    }

    /**
     * Actualizar el estado de las credenciales a "Entregado"
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'status' => 'required|string'
        ]);

        $ids = $request->input('ids');
        $status = $request->input('status');
        
        $docentes = User::whereIn('id', $ids)->get();
        $updated = 0;
        
        foreach ($docentes as $docente) {
            if ($docente->profile) {
                $docente->profile->carnet = $status;
                $docente->profile->save();
                $updated++;
            } else {
                // Si no tiene perfil, crear uno
                $profile = new \App\Models\UserProfile([
                    'user_id' => $docente->id,
                    'carnet' => $status
                ]);
                $profile->save();
                $updated++;
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => "{$updated} credenciales actualizadas correctamente",
            'updated_count' => $updated
        ]);
    }
}
