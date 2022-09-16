<?php

namespace App\Http\Controllers\Gastos;

use App\Http\Controllers\Orm\OrmController;
use App\OrmModel\Gastos\Banco;
use App\OrmModel\Gastos\Cuenta;
use App\OrmModel\Gastos\Gasto;
use App\OrmModel\Gastos\GlosaTipoGasto;
use App\OrmModel\Gastos\SaldoMes;
use App\OrmModel\Gastos\TipoCuenta;
use App\OrmModel\Gastos\TipoGasto;
use App\OrmModel\Gastos\TipoMovimiento;

class ConfigController extends OrmController
{
    protected string $routeName = 'gastosConfig';

    protected array $menuModulo = [
        Banco::class,
        TipoCuenta::class,
        Cuenta::class,
        TipoMovimiento::class,
        TipoGasto::class,
        GlosaTipoGasto::class,
        SaldoMes::class,
        Gasto::class,
    ];
}
