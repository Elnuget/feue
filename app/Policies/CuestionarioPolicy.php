<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Cuestionario;
use Illuminate\Auth\Access\HandlesAuthorization;

class CuestionarioPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Cuestionario $cuestionario)
    {
        return $user->hasRole(1) || $user->hasRole('Docente');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Cuestionario $cuestionario)
    {
        return $user->hasRole(1) || $user->hasRole('Docente');
    }
} 