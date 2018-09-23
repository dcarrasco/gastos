<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\SaldoMes;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Cuenta;
use App\OrmModel\Gastos\TipoGasto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddGastoRequest;

class IngresoGastos extends Controller
{
    public function showMes(Request $request)
    {
        $formCuenta = (new Cuenta)->getFormCuenta($request);
        $formAnno = $this->getFormAnno($request);
        $formMes = $this->getFormMes($request);
        $formTipoGasto = (new TipoGasto)->getFormTipoGasto($request);

        $movimientosMes = (new Gasto)->movimientosMes($request);
        $saldoMesAnterior = (new SaldoMes)->getSaldoMesAnterior($request);

        return view('gastos.showmes', compact(
            'formCuenta', 'formAnno', 'formMes', 'movimientosMes', 'formTipoGasto', 'saldoMesAnterior'
        ));
    }

    public function addGasto(AddGastoRequest $request)
    {
        $gasto = (new Gasto)->fill($request->all());
        $gasto->tipo_movimiento_id = $gasto->tipoGasto->tipo_movimiento_id;
        $gasto->usuario_id = auth()->id();
        $gasto->save();

        $saldoMesAnterior = (new SaldoMes)->getSaldoMesAnterior($request);
        if (is_null($saldoMesActual = (new SaldoMes)->getSaldoMes($request))) {
            $saldoMesActual = (new SaldoMes)->fill(
                array_merge($request->all(), [
                    'saldo_inicial' => $saldoMesAnterior,
                    'saldo_final' => $saldoMesAnterior,
                ])
            );
        }
        $saldoMesActual->saldo_final += $gasto->monto*$gasto->tipoMovimiento->signo;
        $saldoMesActual->save();

        return redirect()->route('gastos.showMes', [
            'cuenta_id' => $request->input('cuenta_id'),
            'anno' => $request->input('anno'),
            'mes' => $request->input('mes'),
        ]);
    }

    protected function getFormAnno(Request $request)
    {
        $inputName = 'anno';
        $options = collect(range(Carbon::now()->year, 2010, -1))
            ->mapWithKeys(function($anno) {
                return [$anno => $anno];
            })
            ->all();

        return \Form::select($inputName, $options, $request->input($inputName), ['class' => 'form-control']);
    }

    protected function getFormMes(Request $request)
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

        return \Form::select($inputName, $options, $request->input($inputName), ['class' => 'form-control']);
    }
}
