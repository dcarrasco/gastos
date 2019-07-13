<?php

namespace App\Gastos;

use \Carbon\Carbon;
use App\Gastos\Banco;
use App\Gastos\TipoCuenta;
use Illuminate\Database\Eloquent\Model;

class Cuenta extends Model
{
    protected $fillable = ['banco_id', 'tipo_cuenta_id', 'cuenta'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = 'cta_cuentas';
    }

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
        return static::select(['cta_cuentas.id', 'cuenta'])
            ->join('cta_tipos_cuentas', 'tipo_cuenta_id', 'cta_tipos_cuentas.id')
            ->where('tipo', $tipo)
            ->orderBy('cuenta')
            ->get()
            ->pluck('cuenta', 'id');
    }

    public static function formArrayGastos()
    {
        return static::formArray(TipoCuenta::CUENTA_GASTO);
    }

    public static function formArrayInversiones()
    {
        return static::formArray(TipoCuenta::CUENTA_INVERSION);
    }

    public static function getFormAnno()
    {
        $options = range(Carbon::now()->year, 2015, -1);

        return array_combine($options, $options);
    }

    public static function getFormMes(string $format = 'F')
    {
        return collect(range(1,12))->mapWithKeys(function ($mes) use ($format) {
                return [$mes => trans('fechas.'.Carbon::create(2000, $mes, 1)->format($format))];
            });
    }
}
