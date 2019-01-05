<?php

namespace App\OrmModel\Gastos;

use Carbon\Carbon;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\Gastos\Banco;
use App\OrmModel\OrmField\Text;
use App\OrmModel\Gastos\TipoCuenta;
use App\OrmModel\OrmField\BelongsTo;
use App\Gastos\TipoCuenta as TipoCuentaModel;

class Cuenta extends Resource
{
    public $model = 'App\Gastos\Cuenta';
    public $icono = 'credit-card';
    public $title = 'cuenta';
    public $search = [
        'id', 'cuenta'
    ];

    public $orderBy = 'cuenta';

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('Banco', 'banco', Banco::class)
                ->rules('required'),

            BelongsTo::make('Tipo Cuenta', 'tipoCuenta', TipoCuenta::class)
                ->rules('required'),

            Text::make('Cuenta')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }

    protected function getFormCuenta(Request $request, $filtroTipoCuenta = [])
    {
        return $this->resourceOrderBy($request)->model()
            ->whereIn('tipo_cuenta_id', $filtroTipoCuenta)
            ->get()
            ->mapWithKeys(function($cuenta) {
                return [$cuenta->getKey() => $cuenta->cuenta];
            });
    }

    public function getFormCuentaGastos(Request $request)
    {
        return $this->getFormCuenta($request, TipoCuentaModel::CUENTAS_GASTOS);
    }

    public function getFormCuentaInversiones(Request $request)
    {
        return $this->getFormCuenta($request, TipoCuentaModel::CUENTAS_INVERSIONES);
    }

    public function getFormAnno(Request $request)
    {
        $options = range(Carbon::now()->year, 2010, -1);

        return array_combine($options, $options);
    }

    public function getFormMes(Request $request)
    {
        return collect(range(1,12))
            ->mapWithKeys(function ($mes) {
                return [$mes => Carbon::create(2000, $mes, 1)->format('F')];
            })
            ->all();
    }
}
