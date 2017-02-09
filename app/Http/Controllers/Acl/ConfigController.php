<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    public $routeName = 'aclConfig';

    public $menuModulo = [];

    public function __construct()
    {
        $this->menuModulo = [
            'usuario' => [
                'nombre' => trans('acl_config.menu_usuarios'),
                'url'    => route($this->routeName.'.index', ['usuario']),
                'icono'  => 'user'
            ],
            'app'     => [
                'nombre' => trans('acl_config.menu_aplicaciones'),
                'url'    => route($this->routeName.'.index', ['app']),
                'icono'  => 'folder-o'
            ],
            'rol'     => [
                'nombre' => trans('acl_config.menu_roles'),
                'url'    => route($this->routeName.'.index', ['rol']),
                'icono'  => 'server'
            ],
            'modulo'  => [
                'nombre' => trans('acl_config.menu_modulos'),
                'url'    => route($this->routeName.'.index', ['modulo']),
                'icono'  => 'list-alt'
            ],
        ];

        view()->share('menuModulo', $this->menuModulo);
        view()->share('moduloSelected', empty(request()->segment(2)) ? collect(array_keys($this->menuModulo))->first() : request()->segment(2));
        view()->share('routeName', $this->routeName);
    }


}
