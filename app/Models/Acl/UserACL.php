<?php

namespace App\Models\Acl;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

/**
 * App\Model\UserACL
 * @property string $nombre
 * @property string $username
 * @property string $password
 * @property string $email
 * @property Collection $rol
 */
abstract class UserACL extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;

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
        return 'https://secure.gravatar.com/avatar/' . md5($this->email) . '?size=24';
    }

    public static function scopeUsuario(Builder $query, string $username): Builder
    {
        return $query->where('username', $username);
    }

    public function getMenuApp(Request $request): Collection
    {
        if (!session()->has('menuapp')) {
            session(['menuapp' => $this->getMenuAppFromDB()]);
        }

        return $this->setSelectedMenu($request, session('menuapp'));
    }

    protected function getMenuAppFromDB(): Collection
    {
        return $this->rol
            ->flatMap->modulo
            ->map(fn($modulo) => $modulo->setAttribute('orden', "{$modulo->app->orden}-{$modulo->orden}")
                    ->setAttribute('selected', false))
            ->sortBy('orden')
            ->values();
    }

    protected function setSelectedMenu(Request $request, Collection $menuApp): Collection
    {
        $currentRoute = config(
            'invfija.' . str_replace('.', '_', $request->route()->getName()),
            $request->route()->getName()
        );

        return $menuApp->map(fn($modulo) => $modulo->setAttribute('selected', $modulo->url === $currentRoute));
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

    protected function getCurrentModulo(string $url): Modulo
    {
        return $this->rol
            ->flatMap->modulo
            ->first(fn($modulo) => Str::contains($url, route($modulo->url)));
    }

    protected function getAclAbilities(): Collection
    {
        if (is_null($modulo = $this->getCurrentModulo(request()->url()))) {
            return collect([]);
        }

        return collect(json_decode($modulo->pivot->abilities) ?? []);
    }

    public function hasAbility(string $ability): bool
    {
        return $this->getAclAbilities()->contains($ability);
    }

}
