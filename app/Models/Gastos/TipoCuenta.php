<?php

namespace App\Models\Gastos;

use App\Models\Gastos\Cuenta;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TipoCuenta extends Model
{
    use HasFactory;

    protected $table = 'cta_tipos_cuentas';

    protected $fillable = ['tipo_movimiento_id', 'tipo_cuenta', 'tipo'];

    public const CUENTA_GASTO = 1;
    public const CUENTA_INVERSION = 2;

    public function cuentas()
    {
        return $this->hasMany(Cuenta::class);
    }
}
