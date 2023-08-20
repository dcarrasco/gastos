<?php

namespace App\Models\Acl;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use App\OrmModel\src\Resource;

/**
 * App\Model\UserACL
 *
 * @property int $id
 * @property string $nombre
 * @property int $activo
 * @property string $username
 * @property string $password
 * @property string $email
 * @property Collection $rol
 */
abstract class UserACL extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;

    /**
     * @return BelongsToMany<Rol>
     */
    public function rol(): BelongsToMany
    {
        return $this->belongsToMany(Rol::class, 'acl_usuario_rol')->withTimestamps();
    }

    public function getFirstName(): string
    {
        return head(explode(' ', $this->nombre));
    }

    public function avatarLink(): string
    {
        return 'https://secure.gravatar.com/avatar/'.md5($this->email).'?size=24';
    }

    /**
     * @param  Builder<UserACL>  $query
     * @return Builder<UserACL>
     */
    public static function scopeUsuario(Builder $query, string $username): Builder
    {
        return $query->where('username', $username);
    }

    /**
     * @return Collection<array-key, Modulo>
     */
    public function getMenuApp(Request $request): Collection
    {
        if (! session()->has('menuapp')) {
            session(['menuapp' => $this->getMenuAppFromDB()]);
        }

        return $this->setSelectedMenu($request, session('menuapp'));
    }

    /**
     * General menuApp desde datos de la BD
     *
     * @return Collection<array-key, Modulo>
     */
    protected function getMenuAppFromDB(): Collection
    {
        return $this->rol
            ->flatMap->modulo
            ->map(fn ($modulo) => $modulo
                    ->setAttribute('orden', "{$modulo->app->orden}-{$modulo->orden}")
                    ->setAttribute('selected', false))
            ->sortBy('orden')
            ->values();
    }

    protected function getCurrentUrl(Request $request): string
    {
        return config(
            'invfija.'.str_replace('.', '_', $request->route()->getName()),
            $request->route()->getName()
        );
    }

    /**
     * Determina el modulo seleccionado en menuApp
     *
     * @param  Request  $request
     * @param  Collection<array-key, Modulo>  $menuApp
     * @return Collection<array-key, Modulo>
     */
    protected function setSelectedMenu(Request $request, Collection $menuApp): Collection
    {
        return $menuApp->map(fn ($modulo) => $modulo
            ->setAttribute('selected', $modulo->url === $this->getCurrentUrl($request))
        );
    }

    public function moduloAppName(Request $request): HtmlString
    {
        return is_null($elem = $this->getMenuApp($request)->first->selected)
            ? new HtmlString('')
            : new HtmlString("<i class=\"fa fa-{$elem->icono} fa-fw\"></i>&nbsp;{$elem->modulo}");
    }

    public function checkPassword(string $password): bool
    {
        return password_verify($password, $this->password);
    }

    public function storePassword(string $password): bool
    {
        $this->password = bcrypt($password);

        return $this->save();
    }

    public function hasPassword(): bool
    {
        return ! empty($this->password);
    }

    protected function getCurrentModulo(Request $request): Modulo|null
    {
        return $this->getMenuApp($request)
            ->first(fn ($modulo) => $modulo->selected);
    }

    /**
     * Devuelve las abilities de pagina actual
     *
     * @param  Request  $request
     * @return Collection<array-key, string>
     */
    protected function getAclAbilities(Request $request): Collection
    {
        if (is_null($modulo = $this->getCurrentModulo($request))) {
            return collect();
        }

        return collect(json_decode($modulo->pivot->abilities) ?: []);
    }

    public function hasAbility(string $ability, Request $request): bool
    {
        return $this->getAclAbilities($request)->contains($ability);
    }


    /**
     * Recupera breadcumbs
     *
     * @return Collection<array-key, array<array-key, string>>
     */
    public function getBreadcrumbs(?Resource $resource, ?string $accion): Collection
    {
        $breadcrumbs = collect()->push(['texto' => config('invfija.app_nombre'), 'url' => '']);

        $modulo = session('menuapp')
            ->first(fn($modulo) => $modulo->url == $this->getCurrentUrl(request()));
        if ($modulo) {
            $breadcrumbs->push(['texto' => $modulo->modulo, 'url' => route($modulo->url)]);
        }

        if ($resource) {
            $breadcrumbs->push([
                'texto' => $resource->getLabelPlural(),
                'url' => route($modulo->url) . '/' . class_basename($resource)
            ]);
        }

        if ($accion) {
            $breadcrumbs->push(['texto' => $accion, 'url' => '']);
        }

        return $breadcrumbs;
    }
}
