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
     * @param  \App\Models\Acl\Usuario  $usuario
     * @return mixed
     */
    public function viewAny(Usuario $usuario)
    {
        return $usuario->hasAbility('view-any', request());
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\Acl\Usuario  $usuario
     * @param  \App\Models\Acl\Modulo  $model
     * @return mixed
     */
    public function view(Usuario $usuario, $model)
    {
        return $usuario->hasAbility('view', request());
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Acl\Usuario  $usuario
     * @return mixed
     */
    public function create(Usuario $usuario)
    {
        return $usuario->hasAbility('create', request());
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\Acl\Usuario  $usuario
     * @param  \App\Models\Acl\Modulo  $model
     * @return mixed
     */
    public function update(Usuario $usuario, $model)
    {
        return $usuario->hasAbility('update', request());
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\Acl\Usuario  $usuario
     * @param  \App\Models\Acl\Modulo  $model
     * @return mixed
     */
    public function delete(Usuario $usuario, $model)
    {
        return $usuario->hasAbility('delete', request());
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\Acl\Usuario  $usuario
     * @param  \App\Models\Acl\Modulo  $model
     * @return mixed
     */
    public function restore(Usuario $usuario, $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\Acl\Usuario  $usuario
     * @param  \App\Models\Acl\Modulo  $model
     * @return mixed
     */
    public function forceDelete(Usuario $usuario, $model)
    {
        //
    }
}
