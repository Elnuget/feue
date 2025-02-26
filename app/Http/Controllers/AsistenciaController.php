<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use App\Models\Matricula;
use App\Models\Curso;
use App\Models\TipoCurso;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function __construct()
    {
        // Establecer la zona horaria por defecto para toda la aplicación
        date_default_timezone_set('America/Guayaquil');
    }

    public function index()
    {
        $cursos = Curso::all();
        $tiposCursos = TipoCurso::all();
        return view('asistencias.index', compact('cursos', 'tiposCursos'));
    }

    public function getAsistencias(Request $request)
    {
        $request->validate([
            'anio' => 'required|integer',
            'mes' => 'required|integer',
            'curso_id' => 'required|integer'
        ]);

        $matriculas = Matricula::where('curso_id', $request->curso_id)
            ->with(['usuario' => function($query) {
                $query->orderBy('name');
            }])
            ->get();

        $asistencias = Asistencia::whereIn('user_id', $matriculas->pluck('usuario_id'))
            ->whereYear('fecha_hora', $request->anio)
            ->whereMonth('fecha_hora', $request->mes)
            ->get();

        return response()->json([
            'matriculas' => $matriculas,
            'asistencias' => $asistencias
        ]);
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
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i|after:hora_entrada',
            'estado' => 'nullable|in:presente,ausente,tardanza,fuga',
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
            'hora_entrada' => 'nullable|date_format:H:i',
            'hora_salida' => 'nullable|date_format:H:i|after:hora_entrada',
            'estado' => 'nullable|in:presente,ausente,tardanza,fuga',
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
        $users = User::with('profile')->get();
        $matriculas = Matricula::with(['curso', 'usuario'])->get();
        $asistencias = Asistencia::with('user')->get();

        return view('asistencias.scan', compact('users', 'matriculas', 'asistencias'));
    }

    private function getHorarioCurso($horario)
    {
        // Patrones para diferentes formatos de horario
        $patrones = [
            // Formato: "12h25-13h00"
            '/^(\d{2})h(\d{2})-(\d{2})h(\d{2})$/',
            
            // Otros formatos existentes...
            '/^(\d{2})h(\d{2})\s+a\s+(\d{2})h(\d{2})$/',
            '/^[A-Za-zá-úÁ-Ú\s-]+,\s*(\d{2})h(\d{2})\s+a\s+(\d{2})h(\d{2})$/',
            '/^[A-Za-zá-úÁ-Ú\s-]+,\s*(\d{2})h(\d{2})-(\d{2})h(\d{2})$/'
        ];

        foreach ($patrones as $patron) {
            if (preg_match($patron, $horario, $matches)) {
                return [
                    'inicio' => [
                        'hora' => (int)$matches[1],
                        'minuto' => (int)$matches[2]
                    ],
                    'fin' => [
                        'hora' => (int)$matches[3],
                        'minuto' => (int)$matches[4]
                    ],
                    'dias' => [0, 1, 2, 3, 4, 5, 6] // Por defecto todos los días
                ];
            }
        }
        return null;
    }

    private function obtenerDiasHorario($horario)
    {
        $diasSemana = [
            'lunes' => 1, 'martes' => 2, 'miércoles' => 3, 'miercoles' => 3,
            'jueves' => 4, 'viernes' => 5, 'sábado' => 6, 'sabado' => 6, 'domingo' => 0
        ];

        $dias = [];
        
        // Si el horario contiene días de la semana
        if (strpos(strtolower($horario), 'lunes') !== false) {
            if (strpos(strtolower($horario), 'viernes') !== false) {
                // Lunes a Viernes
                $dias = [1, 2, 3, 4, 5];
            } else if (strpos(strtolower($horario), 'jueves') !== false) {
                // Lunes a Jueves
                $dias = [1, 2, 3, 4];
            }
        } else if (strpos(strtolower($horario), 'sábado') !== false || 
                   strpos(strtolower($horario), 'sabado') !== false) {
            // Solo sábados
            $dias = [6];
        } else {
            // Si no se especifican días, asumimos que es para todos los días
            $dias = [0, 1, 2, 3, 4, 5, 6];
        }

        return $dias;
    }

    public function registerScan(Request $request)
    {
        try {
            $data = $request->input('data');
            $horaActual = $request->input('hora_actual') ? 
                         Carbon::parse($request->input('hora_actual'))->setTimezone('America/Guayaquil') : 
                         now()->setTimezone('America/Guayaquil');
            
            if (!$data) {
                return response()->json([
                    'success' => false,
                    'message' => 'No se recibieron datos del código QR'
                ], 400);
            }

            $userId = $this->decodeQRCodeData($data);
            
            // Verificar si el usuario existe
            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuario no encontrado'
                ], 404);
            }

            // Buscar la última asistencia del usuario para hoy
            $ultimaAsistencia = Asistencia::where('user_id', $userId)
                ->whereDate('fecha_hora', today()->setTimezone('America/Guayaquil'))
                ->latest()
                ->first();
            
            // Si no tiene asistencias hoy o la última tiene hora de salida, crear nueva entrada
            if (!$ultimaAsistencia || ($ultimaAsistencia && $ultimaAsistencia->hora_salida)) {
                $asistencia = Asistencia::create([
                    'user_id' => $userId,
                    'fecha_hora' => $horaActual,
                    'hora_entrada' => $horaActual,
                    'estado' => 'presente'
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Entrada registrada: ' . $horaActual->format('H:i'),
                    'tipo' => 'entrada'
                ]);
            }
            
            // Si tiene una asistencia sin hora de salida, registrar salida
            if ($ultimaAsistencia && !$ultimaAsistencia->hora_salida) {
                $ultimaAsistencia->update([
                    'hora_salida' => $horaActual
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Salida registrada: ' . $horaActual->format('H:i'),
                    'tipo' => 'salida'
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No se pudo determinar el tipo de registro'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la asistencia: ' . $e->getMessage()
            ], 500);
        }
    }

    public function registerMultiple(Request $request)
    {
        try {
            $request->validate([
                'user_ids' => 'required|array',
                'user_ids.*' => 'required|exists:users,id',
                'tipo_registro' => 'required|in:entrada,salida'
            ]);

            $now = now()->setTimezone('America/Guayaquil');
            foreach ($request->user_ids as $userId) {
                $asistencia = Asistencia::where('user_id', $userId)
                    ->whereDate('fecha_hora', today()->setTimezone('America/Guayaquil'))
                    ->first();

                if ($request->tipo_registro === 'entrada') {
                    if (!$asistencia) {
                        $asistencia = Asistencia::create([
                            'user_id' => $userId,
                            'fecha_hora' => $now,
                            'hora_entrada' => $now,
                            'estado' => 'presente'
                        ]);
                    }
                } else if ($asistencia && !$asistencia->hora_salida) {
                    $asistencia->update([
                        'hora_salida' => $now
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Asistencias registradas correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al registrar las asistencias: ' . $e->getMessage()
            ], 500);
        }
    }

    private function decodeQRCodeData($data)
    {
        // Asegurarse de que el dato es un número válido
        if (!is_numeric($data)) {
            throw new \Exception('El código QR no contiene un ID de usuario válido');
        }
        return intval($data);
    }

    public function show(Asistencia $asistencia)
    {
        return view('asistencias.show', compact('asistencia'));
    }
}