<?php

namespace App\Gastos;

use \Carbon\Carbon;
use App\Gastos\Banco;
use App\Gastos\TipoCuenta;
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

    protected static function formArray($tipo = 0)
    {
        return TipoCuenta::where('tipo', $tipo)->with('cuentas')->get()
            ->map->cuentas
            ->collapse()
            ->pluck('cuenta', 'id');
    }

    public static function selectCuentasGastos()
    {
        return static::formArray(TipoCuenta::CUENTA_GASTO);
    }

    public static function selectCuentasInversiones()
    {
        return static::formArray(TipoCuenta::CUENTA_INVERSION);
    }

    public static function selectAnnos()
    {
        $options = range(Carbon::now()->year, 2015, -1);

        return array_combine($options, $options);
    }

    public static function selectMeses(string $format = 'F')
    {
        return collect(range(1,12))->mapWithKeys(function ($mes) use ($format) {
                return [$mes => trans('fechas.'.Carbon::create(2000, $mes, 1)->format($format))];
            });
    }
}
