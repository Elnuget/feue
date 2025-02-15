<?php

namespace App\Http\Controllers;

use App\Models\Cuestionario;
use App\Models\AulaVirtual;
use App\Models\IntentoCuestionario;
use App\Models\RespuestaUsuario;
use Illuminate\Http\Request;
use Carbon\Carbon;

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

                // Log para debug
                \Log::info('Datos de preguntas recibidos:', [
                    'preguntas' => $request->preguntas
                ]);

                foreach ($request->preguntas as $preguntaData) {
                    // Validar datos básicos de la pregunta
                    if (empty($preguntaData['pregunta']) || empty($preguntaData['tipo'])) {
                        throw new \Exception('Datos de pregunta incompletos');
                    }

                    $pregunta = $cuestionario->preguntas()->create([
                        'pregunta' => $preguntaData['pregunta'],
                        'tipo' => $preguntaData['tipo'],
                    ]);

                    if ($preguntaData['tipo'] === 'verdadero_falso') {
                        if (!isset($preguntaData['respuesta_correcta'])) {
                            throw new \Exception('No se especificó la respuesta correcta para verdadero/falso');
                        }

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
                        if (!isset($preguntaData['opciones']) || !is_array($preguntaData['opciones'])) {
                            throw new \Exception('No se encontraron opciones para opción múltiple');
                        }

                        if (!isset($preguntaData['opciones_correcta'])) {
                            throw new \Exception('No se especificó la opción correcta');
                        }

                        foreach ($preguntaData['opciones'] as $index => $opcion) {
                            if (empty($opcion['texto'])) continue;

                            $pregunta->opciones()->create([
                                'texto' => $opcion['texto'],
                                'es_correcta' => (string)$index === (string)$preguntaData['opciones_correcta']
                            ]);
                        }
                    }

                    // Log para debug
                    \Log::info('Pregunta creada:', [
                        'pregunta_id' => $pregunta->id,
                        'tipo' => $pregunta->tipo,
                        'opciones' => $pregunta->opciones()->get()
                    ]);
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
} 