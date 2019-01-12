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

    protected function formArray($filtroTipoCuenta = 0)
    {
        return $this->select(['cta_cuentas.id', 'cuenta'])
            ->join('cta_tipos_cuentas', 'tipo_cuenta_id', 'cta_tipos_cuentas.id')
            ->where('tipo', $filtroTipoCuenta)
            ->orderBy('cuenta')
            ->get()
            ->pluck('cuenta', 'id');
    }

    public function formArrayGastos()
    {
        return $this->formArray(TipoCuenta::CUENTA_GASTO);
    }

    public function formArrayInversiones()
    {
        return $this->formArray(TipoCuenta::CUENTA_INVERSION);
    }

    public function getFormAnno()
    {
        $options = range(Carbon::now()->year, 2010, -1);

        return array_combine($options, $options);
    }

    public function getFormMes(string $format = 'F')
    {
        return collect(range(1,12))->mapWithKeys(function ($mes) use ($format) {
                return [$mes => Carbon::create(2000, $mes, 1)->format($format)];
            });
    }
}
