<?php

namespace App\Gastos;

use App\Gastos\TipoMovimiento;
use Illuminate\Database\Eloquent\Model;

class TipoCuenta extends Model
{
    protected $table = 'cta_tipos_cuentas';

    protected $fillable = ['tipo_movimiento_id', 'tipo_cuenta', 'tipo'];

    const CUENTA_GASTO = 1;
    const CUENTA_INVERSION = 2;

}
