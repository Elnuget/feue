<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use App\Models\Matricula;
use App\Models\Curso;
use App\Models\TipoCurso;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
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

    public function registerScan(Request $request)
    {
        try {
            $data = $request->input('data');
            
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

            // Registrar la asistencia
            Asistencia::create([
                'user_id' => $userId,
                'fecha_hora' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asistencia registrada correctamente'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar la asistencia: ' . $e->getMessage()
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