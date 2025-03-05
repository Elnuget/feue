<?php

namespace App\Http\Controllers;

use App\Models\Tarea;
use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EntregaController extends Controller
{
    public function store(Request $request, Tarea $tarea)
    {
        $request->validate([
            'archivo' => 'nullable|file|max:10240', // 10MB máximo
            'enlace' => 'nullable|url'
        ]);

        // Verificar que al menos se haya proporcionado un archivo o un enlace
        if (!$request->hasFile('archivo') && !$request->enlace) {
            return back()->with('error', 'Debes proporcionar un archivo o un enlace.');
        }

        // Verificar que no haya una entrega previa
        if ($tarea->entregas()->where('user_id', auth()->id())->exists()) {
            return back()->with('error', 'Ya has realizado una entrega para esta tarea.');
        }

        // Verificar que la tarea esté activa y dentro del plazo
        if ($tarea->estado !== 'activo') {
            return back()->with('error', 'No se pueden realizar entregas para esta tarea porque está inactiva.');
        }
        
        if ($tarea->fecha_limite->isPast()) {
            return back()->with('error', 'No se pueden realizar entregas para esta tarea porque la fecha límite ha pasado.');
        }

        try {
            $entrega = new Entrega([
                'user_id' => auth()->id(),
                'tarea_id' => $tarea->id,
                'fecha_entrega' => now()
            ]);

            if ($request->hasFile('archivo')) {
                $path = $request->file('archivo')->store('entregas', 'public');
                $entrega->archivo = $path;
            }

            if ($request->enlace) {
                $entrega->enlace = $request->enlace;
            }

            $entrega->save();

            return redirect()->route('aulas_virtuales.show', $tarea->aulaVirtual)
                ->with('success', 'Tarea entregada exitosamente. El docente será notificado de tu entrega.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error al guardar la entrega: ' . $e->getMessage());
        }
    }

    public function destroy(Entrega $entrega)
    {
        // Verificar que el usuario sea el propietario de la entrega
        if ($entrega->user_id !== auth()->id()) {
            return back()->with('error', 'No tienes permiso para eliminar esta entrega.');
        }

        // Verificar que la tarea aún esté activa y dentro del plazo
        if ($entrega->tarea->estado !== 'activo' || $entrega->tarea->fecha_limite->isPast()) {
            return back()->with('error', 'No se puede eliminar la entrega fuera de plazo.');
        }

        // Eliminar el archivo si existe
        if ($entrega->archivo) {
            Storage::disk('public')->delete($entrega->archivo);
        }

        $entrega->delete();

        return redirect()->route('aulas_virtuales.show', $entrega->tarea->aulaVirtual)
            ->with('success', 'Entrega eliminada exitosamente.');
    }

    public function calificar(Request $request, Tarea $tarea, Entrega $entrega)
    {
        // Verificación de autenticación
        if (!auth()->check()) {
            return response()->json(['error' => 'Usuario no autenticado'], 401);
        }

        // Verificación de roles
        $user = auth()->user();
        if (!$user->hasRole(1) && !$user->hasRole('Docente')) {
            return response()->json(['error' => 'No tienes permiso para calificar entregas'], 403);
        }

        // Validar que la entrega pertenece a la tarea
        if ($entrega->tarea_id !== $tarea->id) {
            return response()->json(['error' => 'La entrega no corresponde a esta tarea'], 400);
        }

        // Validar la calificación
        $validatedData = $request->validate([
            'calificacion' => 'required|numeric|min:0|max:' . $tarea->puntos_maximos,
            'comentarios' => 'nullable|string|max:1000'
        ], [
            'calificacion.required' => 'La calificación es obligatoria',
            'calificacion.numeric' => 'La calificación debe ser un número',
            'calificacion.min' => 'La calificación no puede ser menor que 0',
            'calificacion.max' => 'La calificación no puede ser mayor que ' . $tarea->puntos_maximos,
            'comentarios.max' => 'Los comentarios no pueden exceder 1000 caracteres'
        ]);

        try {
            $entrega->update([
                'calificacion' => $validatedData['calificacion'],
                'comentarios' => $validatedData['comentarios']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Calificación asignada correctamente',
                'data' => [
                    'id' => $entrega->id,
                    'calificacion' => $entrega->calificacion,
                    'comentarios' => $entrega->comentarios,
                    'puntos_maximos' => $tarea->puntos_maximos,
                    'nombre_estudiante' => $entrega->user->name
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Error al asignar la calificación',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function obtenerEntregas(Tarea $tarea)
    {
        // Verificación de autenticación
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'error' => 'Usuario no autenticado'
            ], 401);
        }

        // Verificación de roles
        $user = auth()->user();
        if (!$user->hasRole(1) && !$user->hasRole('Docente')) {
            return response()->json([
                'success' => false,
                'error' => 'No tienes permiso para ver las entregas'
            ], 403);
        }

        try {
            $entregas = $tarea->entregas()
                ->with('user')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($entrega) {
                    $archivoParts = $entrega->archivo ? explode('/', $entrega->archivo) : [];
                    $archivoNombre = $archivoParts ? end($archivoParts) : null;
                    
                    return [
                        'id' => $entrega->id,
                        'user' => [
                            'id' => $entrega->user->id,
                            'name' => $entrega->user->name,
                            'email' => $entrega->user->email
                        ],
                        'archivo' => $entrega->archivo,
                        'archivo_nombre' => $archivoNombre,
                        'archivo_url' => $entrega->archivo ? Storage::url($entrega->archivo) : null,
                        'enlace' => $entrega->enlace,
                        'calificacion' => $entrega->calificacion,
                        'comentarios' => $entrega->comentarios,
                        'fecha_entrega' => $entrega->fecha_entrega ? $entrega->fecha_entrega->format('Y-m-d H:i:s') : $entrega->created_at->format('Y-m-d H:i:s'),
                        'created_at' => $entrega->created_at->format('Y-m-d H:i:s'),
                        'entregada_a_tiempo' => $entrega->entregadaATiempo(),
                        'esta_calificada' => $entrega->estaCalificada()
                    ];
                });
            
            $entregasCalificadas = $entregas->filter(function ($entrega) {
                return $entrega['esta_calificada'];
            })->count();
            
            return response()->json([
                'success' => true,
                'entregas' => $entregas,
                'tarea' => [
                    'id' => $tarea->id,
                    'titulo' => $tarea->titulo,
                    'puntos_maximos' => $tarea->puntos_maximos,
                    'fecha_limite' => $tarea->fecha_limite->format('Y-m-d H:i:s')
                ],
                'estadisticas' => [
                    'total_entregas' => $entregas->count(),
                    'entregas_calificadas' => $entregasCalificadas,
                    'entregas_pendientes' => $entregas->count() - $entregasCalificadas
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al obtener entregas: ' . $e->getMessage(), [
                'tarea_id' => $tarea->id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'error' => 'Error al obtener las entregas: ' . $e->getMessage()
            ], 500);
        }
    }
} 