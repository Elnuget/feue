<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\AulaVirtual;
use App\Models\IntentoCuestionario;
use App\Models\RespuestaUsuario;
use App\Models\Pregunta;
use App\Models\Opcion;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CuestionarioController extends Controller
{
    public function create(AulaVirtual $aulaVirtual)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        return view('cuestionarios.create', compact('aulaVirtual'));
    }

    public function store(Request $request, AulaVirtual $aulaVirtual)
    {
        if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
            abort(403, 'No tienes permiso para realizar esta acción.');
        }

        try {
            DB::beginTransaction();

            // Log para depuración
            Log::info('Datos recibidos:', [
                'request_all' => $request->all(),
                'preguntas_raw' => $request->preguntas
            ]);

            // Validar datos básicos del cuestionario
            $request->validate([
                'titulo' => 'required|string|max:255',
                'descripcion' => 'nullable|string',
                'tiempo_limite' => 'required|integer|min:1',
                'intentos_permitidos' => 'required|integer|min:1',
                'permite_revision' => 'boolean',
                'activo' => 'boolean',
                'preguntas' => 'required|string'
            ]);

            // Decodificar y validar el JSON de preguntas
            $preguntas = json_decode($request->preguntas, true);
            
            // Log para depuración del JSON decodificado
            Log::info('Preguntas decodificadas:', ['preguntas' => $preguntas]);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('El formato de las preguntas es inválido: ' . json_last_error_msg());
            }

            if (!is_array($preguntas) || empty($preguntas)) {
                throw new \Exception('Debe agregar al menos una pregunta');
            }

            // Crear el cuestionario
            $cuestionario = Cuestionario::create([
                'titulo' => $request->titulo,
                'descripcion' => $request->descripcion,
                'tiempo_limite' => $request->tiempo_limite,
                'intentos_permitidos' => $request->intentos_permitidos,
                'permite_revision' => $request->boolean('permite_revision'),
                'activo' => $request->boolean('activo'),
                'aula_virtual_id' => $aulaVirtual->id
            ]);

            // Procesar las preguntas
            foreach ($preguntas as $index => $preguntaData) {
                // Log para depuración de cada pregunta
                Log::info("Procesando pregunta #{$index}:", ['pregunta_data' => $preguntaData]);

                // Validar que los campos requeridos existan
                if (!isset($preguntaData['pregunta']) || !isset($preguntaData['tipo'])) {
                    throw new \Exception("Datos de pregunta incompletos en la pregunta #{$index}");
                }

                // Validar el tipo de pregunta
                if (!in_array($preguntaData['tipo'], ['opcion_multiple', 'verdadero_falso'])) {
                    throw new \Exception("Tipo de pregunta inválido en la pregunta #{$index}");
                }

                // Crear la pregunta
                $pregunta = Pregunta::create([
                    'cuestionario_id' => $cuestionario->id,
                    'pregunta' => $preguntaData['pregunta'],
                    'tipo' => $preguntaData['tipo']
                ]);

                // Crear opciones según el tipo de pregunta
                if ($preguntaData['tipo'] === 'verdadero_falso') {
                    if (!isset($preguntaData['respuesta_correcta'])) {
                        throw new \Exception("Debe seleccionar una respuesta correcta para la pregunta #{$index}");
                    }

                    // Crear opciones para verdadero/falso
                    Opcion::create([
                        'pregunta_id' => $pregunta->id,
                        'texto' => 'Verdadero',
                        'es_correcta' => $preguntaData['respuesta_correcta'] === 'verdadero'
                    ]);
                    Opcion::create([
                        'pregunta_id' => $pregunta->id,
                        'texto' => 'Falso',
                        'es_correcta' => $preguntaData['respuesta_correcta'] === 'falso'
                    ]);
                } else {
                    // Log para depuración de opciones múltiples
                    Log::info("Opciones para pregunta #{$index}:", [
                        'opciones' => $preguntaData['opciones'] ?? 'no_opciones',
                        'opcion_correcta' => $preguntaData['opciones_correcta'] ?? 'no_correcta'
                    ]);

                    // Validar opciones múltiples
                    if (!isset($preguntaData['opciones']) || !is_array($preguntaData['opciones'])) {
                        throw new \Exception("Formato de opciones inválido en la pregunta #{$index}");
                    }

                    if (count($preguntaData['opciones']) < 2) {
                        throw new \Exception("La pregunta #{$index} debe tener al menos 2 opciones");
                    }

                    if (!isset($preguntaData['opciones_correcta'])) {
                        throw new \Exception("No se ha seleccionado una respuesta correcta para la pregunta #{$index}");
                    }

                    // Crear opciones para opción múltiple
                    foreach ($preguntaData['opciones'] as $opcionIndex => $opcion) {
                        if (!isset($opcion['texto']) || trim($opcion['texto']) === '') {
                            throw new \Exception("La opción #{$opcionIndex} de la pregunta #{$index} no puede estar vacía");
                        }

                        Opcion::create([
                            'pregunta_id' => $pregunta->id,
                            'texto' => $opcion['texto'],
                            'es_correcta' => (string)$opcionIndex === (string)$preguntaData['opciones_correcta']
                        ]);
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Cuestionario guardado exitosamente',
                'cuestionario_id' => $cuestionario->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear cuestionario: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al guardar el cuestionario: ' . $e->getMessage()
            ], 422);
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
            'tiempo_limite' => 'required|integer|min:1',
            'intentos_permitidos' => 'required|integer|min:1',
            'permite_revision' => 'required|boolean',
            'aleatorio' => 'required|boolean',
            'activo' => 'required|boolean'
        ]);

        $cuestionario->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Configuración actualizada correctamente'
        ]);
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

        try {
            $aulaVirtual = $cuestionario->aulaVirtual;
            $cuestionario->delete();

            return redirect()
                ->route('aulas_virtuales.show', $aulaVirtual)
                ->with('success', 'El cuestionario ha sido eliminado exitosamente.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'No se pudo eliminar el cuestionario. Por favor, intente nuevamente.');
        }
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

    public function updatePregunta(Request $request, Pregunta $pregunta)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'pregunta' => 'required|string',
                'tipo' => 'required|in:opcion_multiple,verdadero_falso',
                'opciones' => 'required_if:tipo,opcion_multiple|array',
                'opciones_correcta' => 'required_if:tipo,opcion_multiple',
                'respuesta_correcta' => 'required_if:tipo,verdadero_falso|in:verdadero,falso'
            ]);

            $pregunta->update([
                'pregunta' => $request->pregunta,
                'tipo' => $request->tipo
            ]);

            // Eliminar opciones anteriores
            $pregunta->opciones()->delete();

            if ($request->tipo === 'verdadero_falso') {
                Opcion::create([
                    'pregunta_id' => $pregunta->id,
                    'texto' => 'Verdadero',
                    'es_correcta' => $request->respuesta_correcta === 'verdadero'
                ]);
                Opcion::create([
                    'pregunta_id' => $pregunta->id,
                    'texto' => 'Falso',
                    'es_correcta' => $request->respuesta_correcta === 'falso'
                ]);
            } else {
                foreach ($request->opciones as $index => $opcion) {
                    Opcion::create([
                        'pregunta_id' => $pregunta->id,
                        'texto' => $opcion['texto'],
                        'es_correcta' => (int)$request->opciones_correcta === $index
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pregunta actualizada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al actualizar pregunta: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al actualizar la pregunta: ' . $e->getMessage()
            ], 422);
        }
    }

    public function destroyPregunta(Pregunta $pregunta)
    {
        try {
            DB::beginTransaction();

            $pregunta->opciones()->delete();
            $pregunta->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pregunta eliminada correctamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar pregunta: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error al eliminar la pregunta: ' . $e->getMessage()
            ], 422);
        }
    }

    public function obtenerPregunta(Pregunta $pregunta)
    {
        try {
            if (!auth()->user()->hasRole(1) && !auth()->user()->hasRole('Docente')) {
                abort(403, 'No tienes permiso para realizar esta acción.');
            }

            $pregunta->load('opciones');
            
            return response()->json([
                'id' => $pregunta->id,
                'pregunta' => $pregunta->pregunta,
                'tipo' => $pregunta->tipo,
                'opciones' => $pregunta->opciones->map(function($opcion) {
                    return [
                        'texto' => $opcion->texto,
                        'es_correcta' => $opcion->es_correcta
                    ];
                }),
                'respuesta_correcta' => $pregunta->tipo === 'verdadero_falso' 
                    ? ($pregunta->opciones->where('es_correcta', true)->first()->texto === 'Verdadero' ? 'verdadero' : 'falso')
                    : null
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener pregunta: ' . $e->getMessage());
            return response()->json([
                'message' => 'Error al cargar la pregunta: ' . $e->getMessage()
            ], 422);
        }
    }

    /**
     * Toggle el estado activo/inactivo del cuestionario
     */
    public function toggle(Cuestionario $cuestionario)
    {
        $this->authorize('update', $cuestionario);
        
        $cuestionario->update([
            'activo' => !$cuestionario->activo
        ]);

        return back()->with('success', 'Estado del cuestionario actualizado correctamente.');
    }

    /**
     * Obtener los resultados del cuestionario
     */
    public function resultados(Cuestionario $cuestionario)
    {
        try {
            $resultados = DB::table('intentos_cuestionario')
                ->join('users', 'intentos_cuestionario.usuario_id', '=', 'users.id')
                ->where('cuestionario_id', $cuestionario->id)
                ->whereNotNull('calificacion') // Solo intentos completados
                ->select(
                    'users.id',
                    'users.name as nombre',
                    'users.email',
                    DB::raw('MAX(calificacion) as mejor_calificacion'),
                    DB::raw('COUNT(*) as intentos_realizados'),
                    DB::raw('MAX(intentos_cuestionario.created_at) as ultimo_intento')
                )
                ->groupBy('users.id', 'users.name', 'users.email')
                ->orderBy('mejor_calificacion', 'desc')
                ->get()
                ->map(function($resultado) {
                    return [
                        'nombre' => $resultado->nombre,
                        'email' => $resultado->email,
                        'mejor_calificacion' => number_format($resultado->mejor_calificacion, 2),
                        'intentos_realizados' => $resultado->intentos_realizados,
                        'ultimo_intento' => $resultado->ultimo_intento
                    ];
                });

            return response()->json([
                'success' => true,
                'resultados' => $resultados
            ]);

        } catch (\Exception $e) {
            \Log::error('Error en resultados del cuestionario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener los resultados: ' . $e->getMessage()
            ], 500);
        }
    }
} 