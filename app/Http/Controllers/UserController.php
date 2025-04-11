<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TipoCurso;
use App\Models\Curso;
use App\Models\Asistencia;
use App\Models\Matricula;
use App\Models\Profile;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $tiposCursos = TipoCurso::all();
        $cursos = Curso::all();
        return view('users.index', compact('users', 'tiposCursos', 'cursos'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        try {
            $validator = validator($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'role_id' => 'nullable|exists:roles,id',
            ], [
                'email.unique' => 'Este correo electrónico ya está registrado.',
                'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error de validación',
                    'errors' => $validator->errors()
                ], 422);
            }

            $user = User::create($request->only('name', 'email', 'password'));
            
            if ($request->filled('role_id')) {
                $role = Role::findById($request->role_id);
                $user->assignRole($role);
            }

            return response()->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'redirect' => route('users.index')
            ]);

        } catch (\Exception $e) {
            \Log::error('Error al crear usuario: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error al crear el usuario',
                'errors' => [
                    'general' => [$e->getMessage()]
                ]
            ], 500);
        }
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $payload = $request->only('name','email');
        if ($request->filled('password')) {
            $payload['password'] = $request->password;
        }
        $user->update($payload);

        if ($request->filled('role_id')) {
            $role = Role::findById($request->role_id);
            $user->syncRoles($role);
        } else {
            $user->syncRoles([]);
        }

        return redirect()->route('users.index');
    }

    public function destroy(User $user)
    {
        try {
            DB::beginTransaction();

            // Verificar si es el último administrador
            if ($user->hasRole('Admin') && User::role('Admin')->count() <= 1) {
                return response()->json([
                    'message' => 'No se puede eliminar el último administrador del sistema'
                ], 422);
            }

            // Verificar si el usuario intenta eliminarse a sí mismo
            if ($user->id === auth()->id()) {
                return response()->json([
                    'message' => 'No puedes eliminarte a ti mismo'
                ], 422);
            }

            // Eliminar pagos asociados a las matrículas del usuario
            $matriculasIds = $user->matriculas()->pluck('id');
            \App\Models\Pago::whereIn('matricula_id', $matriculasIds)->delete();

            // Eliminar matrículas del usuario
            $user->matriculas()->delete();

            // Eliminar perfil académico
            \App\Models\UserAcademico::where('user_id', $user->id)->delete();

            // Eliminar aspiraciones universitarias
            \App\Models\UserAspiracion::where('user_id', $user->id)->delete();

            // Eliminar perfil de usuario y archivos asociados
            if ($user->profile) {
                // Eliminar archivos almacenados
                if ($user->profile->photo) {
                    \Storage::disk('public')->delete($user->profile->photo);
                }
                if ($user->profile->acta_grado) {
                    \Storage::disk('public')->delete($user->profile->acta_grado);
                }
                $user->profile->delete();
            }

            // Eliminar roles asociados
            $user->roles()->detach();

            // Finalmente eliminar el usuario
            $user->delete();

            DB::commit();
            
            return response()->json([
                'message' => 'Usuario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al eliminar usuario: ' . $e->getMessage());
            
            return response()->json([
                'message' => 'Error al eliminar el usuario: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showQR(User $user)
    {
        // Generate QR code logic here
        return view('users.qr', compact('user'));
    }

    /**
     * Buscar usuarios por nombre
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');
        
        $users = User::where('name', 'like', "%{$query}%")
                    ->with('profile')
                    ->take(10)
                    ->get();

        return response()->json(['users' => $users]);
    }

    /**
     * Obtener información detallada del usuario
     */
    public function getInfo($id)
    {
        try {
            Log::info('Iniciando getInfo para usuario ID: ' . $id);
            
            // Verificar si el usuario existe
            $user = User::with('roles')->find($id);
            
            if (!$user) {
                Log::error('Usuario no encontrado: ' . $id);
                return response()->json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }

            // Cargar el perfil si existe
            $profile = $user->profile;
            Log::info('Usuario encontrado: ' . $user->name . ', tiene perfil: ' . ($profile ? 'Sí' : 'No'));

            // Verificar si el usuario es docente
            $esDocente = $user->hasRole('Docente');
            Log::info('El usuario es docente: ' . ($esDocente ? 'Sí' : 'No'));

            // Obtener asistencias según el tipo de usuario
            if ($esDocente) {
                // Para docentes, obtener asistencias de la tabla asistencias_docentes
                $asistencias = \App\Models\AsistenciaDocente::where('user_id', $id)
                    ->orderBy('fecha', 'desc')
                    ->orderBy('hora_entrada', 'desc')
                    ->get()
                    ->map(function($asistencia) {
                        // Adaptar el formato para que sea compatible con lo que espera el frontend
                        return [
                            'id' => $asistencia->id,
                            'fecha' => $asistencia->fecha,
                            'hora_entrada' => $asistencia->hora_entrada,
                            'estado' => $asistencia->estado
                        ];
                    });
            } else {
                // Para estudiantes, obtener asistencias de la tabla asistencias (comportamiento original)
                $asistencias = Asistencia::where('user_id', $id)
                    ->orderBy('fecha_hora', 'desc')
                    ->get();
            }

            Log::info('Asistencias encontradas: ' . $asistencias->count());

            // Obtener matrículas con cursos
            $matriculas = Matricula::with(['curso', 'pagos'])
                                 ->where('usuario_id', $id)
                                 ->get();

            Log::info('Matrículas encontradas: ' . $matriculas->count());

            // Preparar la respuesta
            $response = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'roles' => $user->roles->map(function($role) {
                        return [
                            'id' => $role->id,
                            'name' => $role->name
                        ];
                    }),
                    'profile' => $profile ? [
                        'id' => $profile->id,
                        'photo' => $profile->photo ? asset('storage/' . $profile->photo) : null
                    ] : null
                ],
                'asistencias' => $asistencias->values(),
                'matriculas' => $matriculas->map(function($matricula) {
                    return [
                        'id' => $matricula->id,
                        'valor_pendiente' => $matricula->valor_pendiente,
                        'tipo_pago' => $matricula->tipo_pago,
                        'created_at' => $matricula->created_at,
                        'pagos' => $matricula->pagos->map(function($pago) {
                            return [
                                'id' => $pago->id,
                                'monto' => $pago->monto,
                                'fecha_pago' => $pago->fecha_pago,
                                'estado' => $pago->estado
                            ];
                        }),
                        'curso' => $matricula->curso ? [
                            'id' => $matricula->curso->id,
                            'nombre' => $matricula->curso->nombre,
                            'horario' => $matricula->curso->horario ?? '',
                            'sede' => $matricula->curso->sede ?? 'Sede Principal'
                        ] : null
                    ];
                })->values()
            ];

            // Si es docente, incluir información de sesiones del mes actual
            if ($esDocente) {
                // Obtener mes actual
                $mesActual = now()->format('Y-m');
                $inicioMes = now()->startOfMonth()->format('Y-m-d');
                $finMes = now()->endOfMonth()->format('Y-m-d');
                
                Log::info("Buscando sesiones para el docente en el mes: {$mesActual} ({$inicioMes} - {$finMes})");
                
                // Obtener sesiones donde el docente está asignado
                $sesiones = \App\Models\SesionDocente::with('curso')
                    ->where('user_id', $id)
                    ->whereBetween('fecha', [$inicioMes, $finMes])
                    ->orderBy('fecha', 'desc')
                    ->get();
                
                Log::info('Sesiones encontradas para el docente: ' . $sesiones->count());
                
                $response['sesiones'] = $sesiones->map(function($sesion) {
                    return [
                        'id' => $sesion->id,
                        'fecha' => $sesion->fecha,
                        'hora_inicio' => $sesion->hora_inicio,
                        'hora_fin' => $sesion->hora_fin,
                        'curso' => $sesion->curso ? [
                            'id' => $sesion->curso->id,
                            'nombre' => $sesion->curso->nombre
                        ] : null
                    ];
                })->values();
            }

            Log::info('Respuesta preparada correctamente');
            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Error al obtener información del usuario: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'error' => 'Error al obtener la información del usuario',
                'message' => $e->getMessage(),
                'debug' => config('app.debug') ? [
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                    'trace' => $e->getTraceAsString()
                ] : null
            ], 500);
        }
    }
}