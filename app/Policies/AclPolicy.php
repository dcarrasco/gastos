<?php

namespace App\Policies;

use App\Models\Acl\Usuario;
use Illuminate\Auth\Access\HandlesAuthorization;

class AclPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     */
    public function viewAny(Usuario $usuario): bool
    {
        return $usuario->hasAbility('view-any', request());
    }

    /**
     * Determine whether the user can view the model.
     *
     */
    public function view(Usuario $usuario, $model): bool
    {
        return $usuario->hasAbility('view', request());
    }

    /**
     * Determine whether the user can create models.
     *
     */
    public function create(Usuario $usuario): bool
    {
        return $usuario->hasAbility('create', request());
    }

    /**
     * Determine whether the user can update the model.
     *
     */
    public function update(Usuario $usuario, $model): bool
    {
        return $usuario->hasAbility('update', request());
    }

    /**
     * Determine whether the user can delete the model.
     *
     */
    public function delete(Usuario $usuario, $model): bool
    {
        return $usuario->hasAbility('delete', request());
    }

    /**
     * Determine whether the user can restore the model.
     *
     */
    public function restore(Usuario $usuario, $model): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     */
    public function forceDelete(Usuario $usuario, $model): bool
    {
        //
    }
}
