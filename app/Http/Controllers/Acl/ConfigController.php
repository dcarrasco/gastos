<?php

namespace App\Http\Controllers\Acl;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    /**
     * Namespace de los modelos ACL
     *
     * @var string
     */
    protected $modelNameSpace = '\\App\\Acl\\';

    /**
     * Crea una nueva instancia del controlador config
     */
    public function __construct()
    {
        $this->routeName  = 'aclConfig';
        $this->menuModulo = [
            'usuario'=> ['nombre'=>trans('acl_config.menu_usuarios'), 'icono'=>'user'],
            'app'    => ['nombre'=>trans('acl_config.menu_aplicaciones'), 'icono'=>'folder-o'],
            'rol'    => ['nombre'=>trans('acl_config.menu_roles'), 'icono'=>'server'],
            'modulo' => ['nombre'=>trans('acl_config.menu_modulos'), 'icono'=>'list-alt'],
        ];

        $this->makeView();
    }
}
