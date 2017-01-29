<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\OrmController;

class AclConfigController extends Controller
{
    use OrmController;

    public $routeName = 'aclConfig';

    public $modelList = [];

    public function __construct()
    {
        $this->modelList = [
            'usuario' => ['nombre' => trans('acl_config.menu_usuarios'),     'icono' => 'user'],
            'app'     => ['nombre' => trans('acl_config.menu_aplicaciones'), 'icono' => 'folder-o'],
            'rol'     => ['nombre' => trans('acl_config.menu_roles'),        'icono' => 'server'],
            'modulo'  => ['nombre' => trans('acl_config.menu_modulos'),      'icono' => 'list-alt'],
        ];

        view()->share('modelList', $this->modelList);
        view()->share('modelSelected', \Request::segment(2));
        view()->share('routeName', $this->routeName);
    }


}
