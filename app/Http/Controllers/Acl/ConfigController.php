<?php

namespace App\Http\Controllers\Acl;

use App\OrmModel\Acl\App;
use App\OrmModel\Acl\Rol;
use App\OrmModel\Acl\Modulo;
use App\OrmModel\Acl\Usuario;
use App\OrmModel\src\Resource;
use Illuminate\Support\Collection;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends OrmController
{
    protected string $routeName = 'aclConfig';

    protected array $menuModulo = [
        Usuario::class,
        App::class,
        Rol::class,
        Modulo::class,
    ];
}
