<?php

namespace App\Http\Controllers\Acl;

use App\OrmModel\Acl\App;
use App\OrmModel\Acl\Rol;
use App\OrmModel\Acl\Modulo;
use Illuminate\Http\Request;
use App\OrmModel\Acl\Usuario;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    /**
     * Crea una nueva instancia del controlador config
     */
    public function __construct()
    {
        $this->routeName  = 'aclConfig';

        $this->menuModulo = [
            new Usuario,
            new App,
            new Rol,
            new Modulo,
        ];

        $this->makeView();
    }
}
