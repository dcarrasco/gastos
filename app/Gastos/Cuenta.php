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

    protected function formArray($filtroTipoCuenta = [])
    {
        return $this->whereIn('tipo_cuenta_id', $filtroTipoCuenta)
            ->orderBy('cuenta')
            ->get()
            ->mapWithKeys(function($cuenta) {
                return [$cuenta->getKey() => $cuenta->cuenta];
            });
    }

    public function formArrayGastos()
    {
        return $this->formArray(TipoCuenta::CUENTAS_GASTOS);
    }

    public function formArrayInversiones()
    {
        return $this->formArray(TipoCuenta::CUENTAS_INVERSIONES);
    }

    public function getFormAnno()
    {
        $options = range(Carbon::now()->year, 2010, -1);

        return array_combine($options, $options);
    }

    public function getFormMes()
    {
        return collect(range(1,12))
            ->mapWithKeys(function ($mes) {
                return [$mes => Carbon::create(2000, $mes, 1)->format('F')];
            })
            ->all();
    }
}
