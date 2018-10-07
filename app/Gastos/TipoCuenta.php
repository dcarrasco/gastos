<?php

namespace App\Gastos;

use App\Gastos\TipoMovimiento;
use Illuminate\Database\Eloquent\Model;

class TipoCuenta extends Model
{
    protected $fillable = ['tipo_movimiento_id', 'tipo_cuenta'];

    const TIPO_CUENTA_CORRIENTE = 1;
    const TIPO_CUENTA_TARJETA_CREDITO = 2;
    const TIPO_CUENTA_INVERSION = 3;

    const CUENTAS_GASTOS = [
        self::TIPO_CUENTA_CORRIENTE,
        self::TIPO_CUENTA_TARJETA_CREDITO,
    ];
    const CUENTAS_INVERSIONES = [
        self::TIPO_CUENTA_INVERSION,
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_tipos_cuentas';
    }
}
