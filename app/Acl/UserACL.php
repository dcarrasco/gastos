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

        return $this->setSelectedMenu(session('menuapp'));
    }

    protected function getMenuAppFromDB()
    {
        $menuDB = $this->rol->flatMap(function ($rol) {
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
        });

        return $menuDB->mapWithKeys(function ($elemMenu) use ($menuDB) {
            return [
                $elemMenu['app_app'] => [
                    'app'      => $elemMenu['app_app'],
                    'icono'    => $elemMenu['app_icono'],
                    'selected' => false,
                    'modulos'  => $menuDB->filter(function ($menuItem) use ($elemMenu) {
                        return $menuItem['app_app'] === $elemMenu['app_app'];
                    })->map(function ($menuItem) {
                        return [
                            'modulo'       => $menuItem['mod_modulo'],
                            'llave_modulo' => $menuItem['mod_llave_modulo'],
                            'icono'        => $menuItem['mod_icono'],
                            'url'          => $menuItem['mod_url'],
                            'selected'     => null,
                        ];
                    })->all(),
                ],
            ];
        })->all();
    }

    protected function setSelectedMenu($menuApp)
    {
        $routeName = Route::currentRouteName();

        return collect($menuApp)->map(function ($appMenu) use ($routeName) {
            $appMenu['modulos'] = collect($appMenu['modulos'])->map(function ($elemModulo) use ($routeName) {
                $elemModulo['selected'] = $elemModulo['url'] === $routeName ? true : false;
                return $elemModulo;
            })->all();
            return $appMenu;
        })->map(function ($elemMenu) {
            $elemMenu['selected'] = collect($elemMenu['modulos'])->reduce(function ($carry, $modulo) {
                return $carry or $modulo['selected'];
            }, false);

            return $elemMenu;
        })->all();
    }

    public function moduloAppName()
    {
        $routeName = Route::currentRouteName();

        return collect($this->getMenuApp())->flatMap(function ($appElem) {
            return $appElem['modulos'];
        })->first(function ($modulo) use ($routeName) {
            return $modulo['url'] === $routeName;
        })['modulo'];
    }
}
