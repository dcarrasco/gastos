<?php

namespace App\Policies;

use App\Models\Acl\Usuario;
use Illuminate\Support\Str;
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
        return $this->hasAclAbility($usuario, 'view-any');
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
        return $this->hasAclAbility($usuario, 'view');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\Acl\Usuario  $usuario
     * @return mixed
     */
    public function create(Usuario $usuario)
    {
        return $this->hasAclAbility($usuario, 'create');
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
        return $this->hasAclAbility($usuario, 'update');
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
        return $this->hasAclAbility($usuario, 'delete');
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

    protected function getCurrentAclModulo(Usuario $usuario)
    {
        return $usuario->rol
            ->map->modulo
            ->flatten()
            ->filter(function ($modulo) {
                return Str::contains(request()->url(), route($modulo->url));
            })
            ->first();
    }

    protected function getAclAbilities(Usuario $usuario): array
    {
        $modulo = $this->getCurrentAclModulo($usuario);

        return $usuario->rol->map->getModuloAbilities($modulo->id)->flatten()->all();
    }

    protected function hasAclAbility(Usuario $usuario, string $ability): bool
    {
        $abilities = $this->getAclAbilities($usuario);

        return collect($abilities)->contains($ability);
    }
}
