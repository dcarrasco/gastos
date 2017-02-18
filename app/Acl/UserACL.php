<?php

namespace App\Acl;

use Illuminate\Auth\Authenticatable;
use App\OrmModel;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Route;

class UserACL extends OrmModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    public function getMenuApp()
    {
        if (!session()->has('menuapp')) {
            session(['menuapp' => $this->getMenuAppFromDB()]);
        }
        return $this->sortMenuAppFromDB(session('menuapp'));
    }

    protected function getMenuAppFromDB()
    {
        return $this->rol->flatMap(function ($rol) {
            return $rol->modulo;
        })->map(function ($modulo) {
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
        })->sort(function ($elem1, $elem2) {
            return $elem1['orden'] < $elem2['orden'] ? -1 : 1;
        })
        ->all();
    }

    protected function sortMenuAppFromDB($arrMenuDB = [])
    {
        $routeName = Route::currentRouteName();

        return collect($arrMenuDB)->mapWithKeys(function ($elemMenu) use ($routeName, $arrMenuDB) {
            return [
                $elemMenu['app_app'] => [
                    'app'      => $elemMenu['app_app'],
                    'icono'    => $elemMenu['app_icono'],
                    'selected' => false,
                    'modulos'  => collect($arrMenuDB)->filter(function ($menuItem) use ($elemMenu) {
                        return $menuItem['app_app'] === $elemMenu['app_app'];
                    })->map(function ($menuItem) use ($routeName) {
                        return [
                            'modulo'       => $menuItem['mod_modulo'],
                            'llave_modulo' => $menuItem['mod_llave_modulo'],
                            'icono'        => $menuItem['mod_icono'],
                            'url'          => $menuItem['mod_url'],
                            'selected'     => $menuItem['mod_url'] === $routeName ? true : false,
                        ];
                    })->all(),
                ]
            ];
        })->map(function ($elemMenu) {
            $elemMenu['selected'] = collect($elemMenu['modulos'])->reduce(function ($carry, $modulo) {
                return $carry or $modulo['selected'];
            }, false);

            return $elemMenu;
        })->all();
    }

    public function moduloAppName()
    {
        if (!session()->has('menuapp')) {
            session(['menuapp' => $this->getMenuAppFromDB()]);
        }

        $routeName = Route::currentRouteName();

        return collect(session('menuapp'))->first(function ($elem) use ($routeName) {
            return $elem['mod_url'] === $routeName;
        })['mod_modulo'];
    }
}
