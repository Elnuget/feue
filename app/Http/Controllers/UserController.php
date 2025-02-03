<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TipoCurso;
use App\Models\Curso; // Add this line
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        $tiposCursos = TipoCurso::all();
        $cursos = Curso::all(); // Add this line
        return view('users.index', compact('users', 'tiposCursos', 'cursos')); // Modify this line
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
            \DB::beginTransaction();

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

            \DB::commit();
            
            return response()->json([
                'message' => 'Usuario eliminado exitosamente'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Error al eliminar usuario: ' . $e->getMessage());
            
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
}