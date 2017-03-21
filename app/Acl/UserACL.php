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

    protected $llaves = [
        'inventario' => [
            'showHoja'     => 'b386b510e56f73e',
            'addLinea'     => 'b386b510e56f73e',
            'reporte'      => '2dfc992232fe108',
            'ajustes'      => 'fda0416c87cceb5',
            'upload'       => 'fda0416c87cceb5',
            'imprimirForm' => 'fda0416c87cceb5',
        ],
        'inventarioConfig'  => [
            'index'  => '46f163ae6eddc0c',
            'edit'   => '46f163ae6eddc0c',
            'create' => '46f163ae6eddc0c',
        ],
        'stock' => [
            'analisisSeries'     => '02173df489952b0',
            'consultaStockMovil' => 'a37f5a1e01ed158',
            'consultaStockFija'  => 'a37f5a1e01ed158',
        ],
        'stockConfig' => [
            'index'  => '46f163ae6eddc0c',
            'edit'   => '46f163ae6eddc0c',
            'create' => '46f163ae6eddc0c',
        ],
        'toa' => [
            'peticion'   => '470d090393a1e7f',
            'controles'  => 'cd3b54ac404725c',
            'consumos'   => '0bbf9db94624559',
            'asignacion' => 'd5db321c52cc9aa',
        ],
        'toaConfig' => [
            'index'  => '80aa1468e0a10ca',
            'edit'   => '80aa1468e0a10ca',
            'create' => '80aa1468e0a10ca',
        ],
        'aclConfig' => [
            'index'  => '4bd0769215f77e7',
            'edit'   => '4bd0769215f77e7',
            'create' => '4bd0769215f77e7',
        ],
    ];

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
        $llaveModulo = $this->getLlaveModulo();

        return collect($menuApp)->map(function ($appMenu) use ($llaveModulo) {
            $appMenu['modulos'] = collect($appMenu['modulos'])->map(function ($elemModulo) use ($llaveModulo) {
                $elemModulo['selected'] = $elemModulo['llave_modulo'] === $llaveModulo ? true : false;
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
        $llaveModulo = $this->getLlaveModulo();

        return collect($this->getMenuApp())->flatMap(function ($appElem) {
            return $appElem['modulos'];
        })->first(function ($modulo) use ($llaveModulo) {
            return $modulo['llave_modulo'] === $llaveModulo;
        })['modulo'];
    }

    protected function getLlaveModulo()
    {
        return array_get($this->llaves, Route::currentRouteName());
    }
}
