<?php

namespace App\Gastos;

use App\Gastos\Banco;
use App\Gastos\TipoCuenta;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $table = 'cta_cuentas';

    protected $fillable = ['banco_id', 'tipo_cuenta_id', 'cuenta'];


    public function banco()
    {
        return $this->belongsTo(Banco::class);
    }

    public function tipoCuenta()
    {
        return $this->belongsTo(TipoCuenta::class);
    }

    protected static function formArray($tipo = 0): Collection
    {
        return TipoCuenta::where('tipo', $tipo)->with('cuentas')->get()
            ->map->cuentas
            ->collapse()
            ->pluck('cuenta', 'id');
    }

    public static function selectCuentasGastos(): Collection
    {
        return static::formArray(TipoCuenta::CUENTA_GASTO);
    }

    public static function selectCuentasInversiones(): Collection
    {
        return static::formArray(TipoCuenta::CUENTA_INVERSION);
    }
}
