<?php

namespace App\Acl;

use DB;
use Route;
use Illuminate\Support\Arr;
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
    use Authenticatable, Authorizable, CanResetPassword;

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
            'orden'        => $appObject->orden.'-'.$modulo->orden,
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

    public static function checkUserPassword($username = '', $password = ''): bool
    {
        $hash = Usuario::usuario($username)->first()->password;

        return password_verify($password, $hash);
    }

    public static function storeUserPassword(string $username = '', string $password = '')
    {
        $usuario = Usuario::usuario($username)->first();
        $usuario->password = bcrypt($password);

        return $usuario->save();
    }

    public static function checkUserHasPassword(string $username = ''): bool
    {
        if (Usuario::usuario($username)->count() === 0) {
            return false;
        }

        return ! empty(Usuario::usuario($username)->first()->password);
    }
}
