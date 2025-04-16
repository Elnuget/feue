<?php

namespace App\Http\Controllers;

use App\Models\AulaVirtual;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AulaVirtualUsuarioController extends Controller
{
    /**
     * Asociar usuarios a un aula virtual.
     */
    public function associate(Request $request, AulaVirtual $aulaVirtual)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $users = User::whereIn('id', $request->user_ids)
                ->where(function($query) {
                    $query->whereHas('roles', function($q) {
                        $q->where('name', 'Docente');
                    })
                    ->orWhereHas('roles', function($q) {
                        $q->where('id', 1); // Administrador
                    });
                })
                ->get();

            if ($users->isEmpty()) {
                return back()->with('error', 'No se encontraron usuarios con los roles especificados');
            }

            // Verificar si alguno de los usuarios ya está asociado
            $existingUsers = $aulaVirtual->usuarios()
                ->whereIn('users.id', $users->pluck('id'))
                ->get();

            if ($existingUsers->isNotEmpty()) {
                $userNames = $existingUsers->pluck('name')->join(', ');
                return back()->with('warning', "Los siguientes usuarios ya están asociados: {$userNames}");
            }

            // Usar attach en lugar de sync para mantener las asociaciones existentes
            $aulaVirtual->usuarios()->attach($users);

            DB::commit();
            return back()->with('success', 'Usuarios asociados correctamente al aula virtual');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al asociar usuarios: ' . $e->getMessage());
        }
    }

    /**
     * Desasociar usuarios de un aula virtual.
     */
    public function disassociate(Request $request, AulaVirtual $aulaVirtual)
    {
        $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id'
        ]);

        try {
            DB::beginTransaction();

            $aulaVirtual->usuarios()->detach($request->user_ids);

            DB::commit();
            return back()->with('success', 'Usuarios desasociados correctamente del aula virtual');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al desasociar usuarios: ' . $e->getMessage());
        }
    }
} 