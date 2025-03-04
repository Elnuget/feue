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
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        $user = User::create($request->only('name', 'email', 'password'));
        if ($request->filled('role_id')) {
            $role = Role::findById($request->role_id);
            $user->assignRole($role);
        }

        return redirect()->route('users.index');
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
            $user = User::find($id);
            
            if (!$user) {
                Log::error('Usuario no encontrado: ' . $id);
                return response()->json([
                    'error' => 'Usuario no encontrado'
                ], 404);
            }

            // Cargar el perfil si existe
            $profile = $user->profile;
            Log::info('Usuario encontrado: ' . $user->name . ', tiene perfil: ' . ($profile ? 'Sí' : 'No'));

            // Obtener asistencias
            $asistencias = Asistencia::where('user_id', $id)
                                   ->orderBy('fecha_hora', 'desc')
                                   ->get();

            Log::info('Asistencias encontradas: ' . $asistencias->count());

            // Obtener matrículas con cursos
            $matriculas = Matricula::with('curso')
                                 ->where('usuario_id', $id)
                                 ->get();

            Log::info('Matrículas encontradas: ' . $matriculas->count());

            // Preparar la respuesta
            $response = [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'profile' => $profile ? [
                        'id' => $profile->id,
                        'photo' => $profile->photo ? asset('storage/' . $profile->photo) : null
                    ] : null
                ],
                'asistencias' => $asistencias->map(function($asistencia) {
                    return [
                        'id' => $asistencia->id,
                        'fecha_hora' => $asistencia->fecha_hora,
                        'hora_entrada' => $asistencia->hora_entrada,
                        'hora_salida' => $asistencia->hora_salida
                    ];
                })->values(),
                'matriculas' => $matriculas->map(function($matricula) {
                    return [
                        'id' => $matricula->id,
                        'valor_pendiente' => $matricula->valor_pendiente,
                        'curso' => $matricula->curso ? [
                            'id' => $matricula->curso->id,
                            'nombre' => $matricula->curso->nombre,
                            'horario' => $matricula->curso->horario ?? '',
                            'sede' => $matricula->curso->sede ?? 'Sede Principal'
                        ] : null
                    ];
                })->values()
            ];

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