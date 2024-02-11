<?php

namespace App\Http\Controllers\Cash;

use App\Http\Controllers\Orm\OrmController;
use App\OrmModel\Cash\Cuenta;
use App\OrmModel\Cash\TipoCuenta;

class ConfigController extends OrmController
{
    protected string $routeName = 'cashConfig';

    protected array $menuModulo = [
        Cuenta::class,
        TipoCuenta::class,
    ];
}
