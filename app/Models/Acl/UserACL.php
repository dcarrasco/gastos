<?php

namespace App\Models\Acl;

use Route;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
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

    public function getMenuApp(): Collection
    {
        if (!session()->has('menuapp')) {
            session(['menuapp' => $this->getMenuAppFromDB()]);
        }

        return $this->setSelectedMenu(session('menuapp'));
    }

    protected function menuAppObject(Modulo $modulo): object
    {
        $appObject = $modulo->app;

        return (object) [
            'orden'        => "{$appObject->orden}-{$modulo->orden}",
            'app'          => $appObject->app,
            'modulo'       => $modulo->modulo,
            'llave_modulo' => $modulo->llave_modulo,
            'icono'        => $modulo->icono,
            'url'          => $modulo->url,
            'selected'     => false,
        ];
    }

    protected function getMenuAppFromDB(): Collection
    {
        return $this->rol
            ->flatMap->modulo
            ->map(function ($modulo) {
                return $this->menuAppObject($modulo);
            })
            ->sort(function ($elem1, $elem2) {
                return $elem1->orden < $elem2->orden ? -1 : 1;
            })
            ->values();
    }

    protected function setSelectedMenu(Collection $menuApp): Collection
    {
        $llaveModulo = $this->getLlaveModulo();

        return $menuApp->map(function ($modulo) use ($llaveModulo) {
            $modulo->selected = ($modulo->llave_modulo === $llaveModulo);

            return $modulo;
        });
    }

    public function moduloAppName(): HtmlString
    {
        return is_null($elem = $this->getMenuApp()->first->selected)
            ? new HtmlString('')
            : new HtmlString("<i class=\"fa fa-{$elem->icono} fa-fw\"></i>&nbsp;{$elem->modulo}");
    }

    protected function getLlaveModulo(): string
    {
        return Arr::get(config('invfija.llavesApp'), Route::currentRouteName() ?? '', '');
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
            ->map->modulo
            ->flatten()
            ->filter(function ($modulo) use ($url) {
                return Str::contains($url, route($modulo->url));
            })
            ->first();
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
