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
        })->all();
    }

    protected function sortMenuAppFromDB($arrMenuDB = [])
    {
        $routeName = Route::currentRouteName();

        array_multisort(array_column($arrMenuDB, 'orden'), $arrMenuDB);

        $arrMenu = [];
        collect($arrMenuDB)->each(function ($elem) use (&$arrMenu, $routeName) {
            if (! array_key_exists($elem['app_app'], $arrMenu)) {
                $arrMenu[$elem['app_app']] = [
                    'app'      => $elem['app_app'],
                    'icono'    => $elem['app_icono'],
                    'selected' => $elem['mod_url'] === $routeName ? true : false,
                    'modulos'  => [],
                ];
            }

            $arrMenu[$elem['app_app']]['modulos'][] = [
                'modulo'       => $elem['mod_modulo'],
                'llave_modulo' => $elem['mod_llave_modulo'],
                'icono'        => $elem['mod_icono'],
                'url'          => $elem['mod_url'],
                'selected'     => $elem['mod_url'] === $routeName ? true : false,
            ];

            $arrMenu[$elem['app_app']]['selected'] = ($arrMenu[$elem['app_app']]['selected'] or ($elem['mod_url'] === $routeName)) ? true : false;
        });

        return $arrMenu;
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
