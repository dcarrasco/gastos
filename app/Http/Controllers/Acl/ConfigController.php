<?php

namespace App\Http\Controllers\Acl;

use App\OrmModel\Acl\App;
use App\OrmModel\Acl\Rol;
use App\OrmModel\Acl\Modulo;
use App\OrmModel\Acl\Usuario;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends OrmController
{
    protected string $routeName = 'aclConfig';

    protected $menuModulo = [
        Usuario::class,
        App::class,
        Rol::class,
        Modulo::class,
    ];
}
