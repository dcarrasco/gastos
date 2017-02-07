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
            'usuario' => ['nombre' => trans('acl_config.menu_usuarios'),     'icono' => 'user'],
            'app'     => ['nombre' => trans('acl_config.menu_aplicaciones'), 'icono' => 'folder-o'],
            'rol'     => ['nombre' => trans('acl_config.menu_roles'),        'icono' => 'server'],
            'modulo'  => ['nombre' => trans('acl_config.menu_modulos'),      'icono' => 'list-alt'],
        ];

        view()->share('menuModulo', $this->menuModulo);
        view()->share('moduloSelected', \Request::segment(2));
        view()->share('routeName', $this->routeName);
        view()->share('moduloRouteName', $this->routeName.'.index');
    }


}
