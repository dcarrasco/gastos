<?php

namespace App\Models\Acl;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserACL extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable;
    use Authorizable;
    use CanResetPassword;

    public static function scopeUsuario($query, $username)
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
            ->map(function ($modulo) {
                return (object) $modulo
                    ->setAttribute('orden', "{$modulo->app->orden}-{$modulo->orden}")
                    ->setAttribute('selected', false)
                    ->toArray();
            })
            ->sortBy('orden')
            ->values();
    }

    protected function setSelectedMenu(Request $request, Collection $menuApp): Collection
    {
        $currentRoute = config(
            'invfija.' . str_replace('.', '_', $request->route()->getName()),
            $request->route()->getName()
        );

        return $menuApp->map(function ($modulo) use ($currentRoute) {
            $modulo->selected = ($modulo->url === $currentRoute);

            return $modulo;
        });
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

    public function storePassword(string $password)
    {
        $this->password = bcrypt($password);

        return $this->save();
    }

    public function hasPassword(): bool
    {
        return ! empty($this->password);
    }

    protected function getCurrentModulo(string $url)
    {
        return $this->rol
            ->flatMap->modulo
            ->first(function ($modulo) use ($url) {
                return Str::contains($url, route($modulo->url));
            });
    }

    public function getAclAbilities(): array
    {
        $modulo = $this->getCurrentModulo(request()->url());

        if (is_null($modulo)) {
            return [];
        }

        return json_decode($modulo->pivot->abilities) ?? [];
    }
}
