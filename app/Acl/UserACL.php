<?php

namespace App\Acl;

use DB;
use Route;
use App\OrmModel;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class UserACL extends OrmModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    public static function scopeUsuario($query, $username)
    {
        return $query->where('username', $username);
    }

    public function getMenuApp()
    {
        if (!session()->has('menuapp')) {
            session(['menuapp' => $this->getMenuAppFromDB()]);
        }

        return $this->setSelectedMenu(session('menuapp'));
    }

    protected function getMenuAppFromDB()
    {
        $menuDB = $this->rol
            ->flatMap(function ($rol) {
                return $rol->modulo;
            })
            ->map(function ($modulo) {
                $appObject = $modulo->app;

                return [
                    'orden'            => $appObject->orden.'-'.$modulo->orden,
                    'app_app'          => $appObject->app,
                    'app_icono'        => $appObject->icono,
                    'app_selected'     => false,
                    'mod_modulo'       => $modulo->modulo,
                    'mod_llave_modulo' => $modulo->llave_modulo,
                    'mod_icono'        => $modulo->icono,
                    'mod_url'          => $modulo->url,
                    'mod_selected'     => false,
                ];
            })
            ->sort(function ($elem1, $elem2) {
                return $elem1['orden'] < $elem2['orden'] ? -1 : 1;
            });

        return $menuDB->mapWithKeys(function ($elemMenu) use ($menuDB) {
            return [
                $elemMenu['app_app'] => [
                    'app'      => $elemMenu['app_app'],
                    'icono'    => $elemMenu['app_icono'],
                    'selected' => false,
                    'modulos'  => $menuDB
                        ->filter(function ($menuItem) use ($elemMenu) {
                            return $menuItem['app_app'] === $elemMenu['app_app'];
                        })
                        ->map(function ($menuItem) {
                            return [
                                'modulo'       => $menuItem['mod_modulo'],
                                'llave_modulo' => $menuItem['mod_llave_modulo'],
                                'icono'        => $menuItem['mod_icono'],
                                'url'          => $menuItem['mod_url'],
                                'selected'     => null,
                            ];
                        })
                        ->all(),
                ],
            ];
        })
        ->all();
    }

    protected function setSelectedMenu($menuApp)
    {
        $llaveModulo = $this->getLlaveModulo();

        return collect($menuApp)
            ->map(function ($appMenu) use ($llaveModulo) {
                $appMenu['modulos'] = collect($appMenu['modulos'])
                    ->map(function ($elemModulo) use ($llaveModulo) {
                        $elemModulo['selected'] = ($elemModulo['llave_modulo'] === $llaveModulo);

                        return $elemModulo;
                    })
                    ->all();

                return $appMenu;
            })
            ->map(function ($elemMenu) {
                $elemMenu['selected'] = collect($elemMenu['modulos'])
                    ->reduce(function ($carry, $modulo) {
                        return $carry or $modulo['selected'];
                    }, false);

                return $elemMenu;
            })
            ->all();
    }

    public function moduloAppName()
    {
        $llaveModulo = $this->getLlaveModulo();

        $elem = collect($this->getMenuApp())
            ->flatMap(function ($appElem) {
                return $appElem['modulos'];
            })
            ->first(function ($modulo) use ($llaveModulo) {
                return $modulo['llave_modulo'] === $llaveModulo;
            });

        return '<i class="fa fa-'.array_get($elem, 'icono').' fa-fw"></i> '.array_get($elem, 'modulo');
    }

    protected function getLlaveModulo()
    {
        return array_get(config('invfija.llavesApp'), Route::currentRouteName());
    }

    public static function checkUserPassword($username = '', $password = '')
    {
        $hash = Usuario::usuario($username)->first()->password;

        return password_verify($password, $hash);
    }

    public static function storeUserPassword($username = '', $password = '')
    {
        $usuario = Usuario::usuario($username)->first();
        $usuario->password = bcrypt($password);

        return $usuario->save();
    }

    public static function checkUserHasPassword($username = '')
    {
        if (Usuario::usuario($username)->count() === 0) {
            return false;
        }

        return ! empty(Usuario::usuario($username)->first()->password);

    }
}
