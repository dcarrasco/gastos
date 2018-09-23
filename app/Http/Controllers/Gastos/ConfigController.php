<?php

namespace App\Http\Controllers\Gastos;

use Illuminate\Http\Request;
use App\OrmModel\Gastos\Banco;
use App\OrmModel\Gastos\Gasto;
use App\OrmModel\Gastos\Cuenta;
use App\OrmModel\Gastos\SaldoMes;
use App\OrmModel\Gastos\TipoGasto;
use App\OrmModel\Gastos\TipoCuenta;
use App\Http\Controllers\Controller;
use App\OrmModel\Gastos\TipoMovimiento;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    /**
     * Crea una nueva instancia del controlador config
     */
    public function __construct()
    {
        $this->routeName  = 'gastosConfig';

        $this->menuModulo = [
            new Banco,
            new TipoCuenta,
            new Cuenta,
            new TipoMovimiento,
            new TipoGasto,
            new SaldoMes,
            new Gasto,
        ];

        $this->makeView();
    }
}
