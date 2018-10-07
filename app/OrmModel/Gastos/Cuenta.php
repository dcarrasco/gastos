<?php

namespace App\OrmModel\Gastos;

use Carbon\Carbon;
use App\Gastos\TipoCuenta as TipoCuentaModel;
use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\Gastos\Banco;
use App\OrmModel\OrmField\Text;
use App\OrmModel\Gastos\TipoCuenta;
use App\OrmModel\OrmField\BelongsTo;

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
        $inputName = 'cuenta_id';

        $options = $this->resourceOrderBy($request)->model()
            ->whereIn('tipo_cuenta_id', $filtroTipoCuenta)
            ->get()
            ->mapWithKeys(function($cuenta) {
                return [$cuenta->getKey() => $cuenta->cuenta];
            });

        return \Form::select($inputName, $options, $request->input($inputName), ['class' => 'form-control']);
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
        $inputName = 'anno';
        $options = collect(range(Carbon::now()->year, 2010, -1))
            ->mapWithKeys(function($anno) {
                return [$anno => $anno];
            })
            ->all();

        return \Form::select($inputName, $options, $request->input($inputName, Carbon::now()->year), ['class' => 'form-control']);
    }

    public function getFormMes(Request $request, $extraParam = [])
    {
        $inputName = 'mes';
        $options = [
            1 => 'Enero',
            2 =>'Febrero',
            3 =>'Marzo',
            4 =>'Abril',
            5 =>'Mayo',
            6 =>'Junio',
            7 =>'Julio',
            8 =>'Agosto',
            9 =>'Septiembre',
            10 =>'Octubre',
            11 =>'Noviembre',
            12 =>'Diciembre',
        ];

        return \Form::select($inputName, $options, $request->input($inputName, Carbon::now()->month), array_merge(['class' => 'form-control'], $extraParam));
    }
}
