<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Orm\OrmController;
use App\OrmModel\Cash\Cuenta;

class ConfigController extends OrmController
{
    protected string $routeName = 'cashConfig';

    protected array $menuModulo = [
        Cuenta::class,
    ];
}
