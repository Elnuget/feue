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
        $user->delete();
        return redirect()->route('users.index');
    }

    public function showQR(User $user)
    {
        // Generate QR code logic here
        return view('users.qr', compact('user'));
    }
}