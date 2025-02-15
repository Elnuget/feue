<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\AulaVirtual;
use App\Models\IntentoCuestionario;
use App\Models\RespuestaUsuario;
use App\Models\Pregunta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class CuestionarioController extends Controller
{
    public function create(AulaVirtual $aulaVirtual)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        // Crear el cuestionario primero con valores por defecto
        $cuestionario = $aulaVirtual->cuestionarios()->create([
            'titulo' => 'Nuevo Cuestionario',
            'activo' => false,
            'tiempo_limite' => 30, // 30 minutos por defecto
            'intentos_permitidos' => 1, // 1 intento por defecto
            'permite_revision' => false
        ]);

        return view('cuestionarios.create', compact('aulaVirtual', 'cuestionario'));
    }

    public function store(Request $request, AulaVirtual $aulaVirtual)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        try {
            \DB::beginTransaction();

            // Si es el primer envío, crear el cuestionario base
            if ($request->input('modo') === 'inicial') {
                $cuestionario = $aulaVirtual->cuestionarios()->create([
                    'titulo' => $request->titulo,
                    'descripcion' => $request->descripcion,
                    'tiempo_limite' => $request->tiempo_limite,
                    'intentos_permitidos' => $request->intentos_permitidos,
                    'permite_revision' => $request->has('permite_revision'),
                    'retroalimentacion' => $request->retroalimentacion,
                    'activo' => true,
                ]);

                \DB::commit();
                return response()->json(['cuestionario_id' => $cuestionario->id]);
            }

            // Si es envío de preguntas
            if ($request->input('modo') === 'preguntas') {
                $cuestionario = Cuestionario::findOrFail($request->cuestionario_id);
                
                if ($cuestionario->aula_virtual_id != $aulaVirtual->id) {
                    throw new \Exception('El cuestionario no pertenece a esta aula virtual');
                }

                foreach ($request->preguntas as $preguntaData) {
                    $pregunta = $cuestionario->preguntas()->create([
                        'pregunta' => $preguntaData['pregunta'],
                        'tipo' => $preguntaData['tipo'],
                    ]);

                    if ($preguntaData['tipo'] === 'verdadero_falso') {
                        $pregunta->opciones()->createMany([
                            [
                                'texto' => 'Verdadero',
                                'es_correcta' => $preguntaData['respuesta_correcta'] === 'verdadero'
                            ],
                            [
                                'texto' => 'Falso',
                                'es_correcta' => $preguntaData['respuesta_correcta'] === 'falso'
                            ]
                        ]);
                    } else {
                        foreach ($preguntaData['opciones'] as $index => $opcion) {
                            if (empty($opcion['texto'])) continue;

                            $pregunta->opciones()->create([
                                'texto' => $opcion['texto'],
                                'es_correcta' => (string)$index === (string)$preguntaData['opciones_correcta']
                            ]);
                        }
                    }
                }

                \DB::commit();
                return response()->json(['success' => true]);
            }

            throw new \Exception('Modo de operación no válido');

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al crear cuestionario:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);
            
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function show(Cuestionario $cuestionario)
    {
        // Cargar el cuestionario con sus relaciones
        $cuestionario->load(['preguntas', 'preguntas.opciones']);

        $intento = IntentoCuestionario::where('cuestionario_id', $cuestionario->id)
            ->where('usuario_id', auth()->id())
            ->whereNull('fin')
            ->first();

        if (!$intento) {
            $intentosRealizados = IntentoCuestionario::where('cuestionario_id', $cuestionario->id)
                ->where('usuario_id', auth()->id())
                ->count();

            if ($intentosRealizados >= $cuestionario->intentos_permitidos) {
                return redirect()->back()
                    ->with('error', 'Has alcanzado el número máximo de intentos permitidos.');
            }

            $intento = IntentoCuestionario::create([
                'cuestionario_id' => $cuestionario->id,
                'usuario_id' => auth()->id(),
                'inicio' => now(),
                'numero_intento' => $intentosRealizados + 1,
            ]);
        }

        $tiempoRestante = Carbon::parse($intento->inicio)
            ->addMinutes($cuestionario->tiempo_limite)
            ->diffInSeconds(now());

        if ($tiempoRestante <= 0) {
            $this->finalizarIntento($intento);
            return redirect()->back()
                ->with('error', 'El tiempo para este intento ha expirado.');
        }

        // Verificar si hay preguntas
        if ($cuestionario->preguntas->isEmpty()) {
            return redirect()->back()
                ->with('error', 'Este cuestionario no tiene preguntas configuradas.');
        }

        // Cargar las respuestas existentes del intento
        $respuestasUsuario = RespuestaUsuario::where('intento_id', $intento->id)
            ->pluck('opcion_id', 'pregunta_id')
            ->toArray();

        return view('cuestionarios.show', compact('cuestionario', 'intento', 'tiempoRestante', 'respuestasUsuario'));
    }

    private function finalizarIntento(IntentoCuestionario $intento)
    {
        $totalPreguntas = $intento->cuestionario->preguntas->count();
        $respuestasCorrectas = 0;

        foreach ($intento->respuestas as $respuesta) {
            if ($respuesta->opcion->es_correcta) {
                $respuestasCorrectas++;
            }
        }

        $calificacion = ($respuestasCorrectas / $totalPreguntas) * 100;

        $intento->update([
            'fin' => now(),
            'calificacion' => $calificacion
        ]);

        return $calificacion;
    }

    public function guardarRespuesta(Request $request, IntentoCuestionario $intento)
    {
        $validated = $request->validate([
            'pregunta_id' => 'required|exists:preguntas,id',
            'opcion_id' => 'required|exists:opciones,id'
        ]);

        $intento->respuestas()->updateOrCreate(
            ['pregunta_id' => $validated['pregunta_id']],
            ['opcion_id' => $validated['opcion_id']]
        );

        return response()->json(['success' => true]);
    }

    public function finalizar(IntentoCuestionario $intento)
    {
        $calificacion = $this->finalizarIntento($intento);

        return redirect()
            ->route('aulas_virtuales.show', $intento->cuestionario->aulaVirtual)
            ->with('success', "Cuestionario finalizado. Tu calificación: {$calificacion}%");
    }

    public function revision(Cuestionario $cuestionario)
    {
        // Verificar si el usuario tiene permiso para ver la revisión
        if (!$cuestionario->permite_revision) {
            return redirect()->back()
                ->with('error', 'La revisión no está habilitada para este cuestionario.');
        }

        // Obtener el último intento finalizado del usuario
        $intento = IntentoCuestionario::where('cuestionario_id', $cuestionario->id)
            ->where('usuario_id', auth()->id())
            ->whereNotNull('fin')
            ->whereNotNull('calificacion')  // Asegurarse de que tenga calificación
            ->latest('fin')  // Ordenar por fecha de finalización
            ->first();

        if (!$intento) {
            return redirect()->back()
                ->with('error', 'No se encontraron intentos completados para revisar.');
        }

        return view('cuestionarios.revision', compact('cuestionario', 'intento'));
    }

    public function toggleEstado(Request $request, Cuestionario $cuestionario)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $cuestionario->update([
            'activo' => $request->activo
        ]);

        return response()->json(['success' => true]);
    }

    public function actualizarConfig(Request $request, Cuestionario $cuestionario)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $validated = $request->validate([
            'campo' => 'required|in:tiempo_limite,intentos_permitidos',
            'valor' => 'required|numeric|min:1'
        ]);

        $cuestionario->update([
            $validated['campo'] => $validated['valor']
        ]);

        return response()->json(['success' => true]);
    }

    public function programar(Request $request, Cuestionario $cuestionario)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $validated = $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after:fecha_inicio'
        ]);

        $cuestionario->update([
            'fecha_inicio' => $validated['fecha_inicio'],
            'fecha_fin' => $validated['fecha_fin']
        ]);

        return response()->json(['success' => true]);
    }

    public function destroy(Cuestionario $cuestionario)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $cuestionario->delete();

        return response()->json(['success' => true]);
    }

    public function edit(Cuestionario $cuestionario)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return view('cuestionarios.edit', compact('cuestionario'));
    }

    public function update(Request $request, Cuestionario $cuestionario)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'nullable|string',
            'tiempo_limite' => 'required|integer|min:1',
            'intentos_permitidos' => 'required|integer|min:1',
            'permite_revision' => 'boolean',
            'retroalimentacion' => 'nullable|string'
        ]);

        $cuestionario->update($validated);

        return redirect()
            ->route('cuestionarios.show', $cuestionario)
            ->with('success', 'Cuestionario actualizado exitosamente');
    }

    public function eliminarPregunta(Pregunta $pregunta)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        $pregunta->delete();

        return response()->json(['success' => true]);
    }

    public function agregarPregunta(Request $request, Cuestionario $cuestionario)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        try {
            \DB::beginTransaction();

            // Crear la pregunta
            $pregunta = $cuestionario->preguntas()->create([
                'pregunta' => $request->pregunta,
                'tipo' => $request->tipo,
            ]);

            // Si es verdadero/falso
            if ($request->tipo === 'verdadero_falso') {
                if (!$request->has('respuesta_correcta')) {
                    throw new \Exception('Debe seleccionar una respuesta correcta');
                }
                
                $pregunta->opciones()->createMany([
                    [
                        'texto' => 'Verdadero',
                        'es_correcta' => $request->respuesta_correcta === 'verdadero'
                    ],
                    [
                        'texto' => 'Falso',
                        'es_correcta' => $request->respuesta_correcta === 'falso'
                    ]
                ]);
            } 
            // Si es opción múltiple
            else {
                if (!$request->has('opciones') || !is_array($request->opciones)) {
                    throw new \Exception('Debe proporcionar opciones para la pregunta');
                }

                foreach ($request->opciones as $index => $opcion) {
                    if (empty($opcion['texto'])) continue;

                    $pregunta->opciones()->create([
                        'texto' => $opcion['texto'],
                        'es_correcta' => (string)$index === (string)$request->opciones_correcta
                    ]);
                }
            }

            \DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json(['message' => $e->getMessage()], 422);
        }
    }

    public function obtenerPreguntas(Cuestionario $cuestionario)
    {
        $preguntas = $cuestionario->preguntas()
            ->with('opciones')
            ->get()
            ->map(function ($pregunta) {
                return [
                    'id' => $pregunta->id,
                    'pregunta' => $pregunta->pregunta,
                    'tipo' => $pregunta->tipo,
                    'imagen_url' => $pregunta->imagen ? Storage::url($pregunta->imagen) : null,
                    'opciones' => $pregunta->opciones->map(function ($opcion) {
                        return [
                            'texto' => $opcion->texto,
                            'es_correcta' => $opcion->es_correcta
                        ];
                    })
                ];
            });

        return response()->json($preguntas);
    }
} 