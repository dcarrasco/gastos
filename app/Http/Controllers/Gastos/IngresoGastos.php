<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\SaldoMes;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Cuenta;
use App\OrmModel\Gastos\TipoGasto;
use App\Http\Controllers\Controller;
use App\OrmModel\Gastos\TipoMovimiento;
use App\Gastos\TipoGasto as TipoGastoModel;
use App\Http\Requests\Gasto\AddGastoRequest;

class IngresoGastos extends Controller
{
    public function showMes(Request $request)
    {
        $formCuenta = (new Cuenta)->getFormCuenta($request);
        $formAnno = (new Cuenta)->getFormAnno($request);
        $formMes = (new Cuenta)->getFormMes($request);
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
        $gasto->fecha = empty($gasto->fecha)
            ? Carbon::now()
                ->year($request->input('anno'))
                ->month($request->input('mes'))
                ->day(1)->hour(0)->minute(0)->second(0)
            : $gasto->fecha;
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

    public function borrarGasto(Request $request)
    {
        $gasto = (new Gasto)->findOrFail($request->id)->delete();

        return redirect()->route('gastos.showMes', [
            'cuenta_id' => $request->input('cuenta_id'),
            'anno' => $request->input('anno'),
            'mes' => $request->input('mes'),
        ]);
    }

    protected function reporte(Request $request)
    {
        $formCuenta = (new Cuenta)->getFormCuenta($request);
        $formAnno = (new Cuenta)->getFormAnno($request);
        $formTipoMovimiento = (new TipoMovimiento)->getFormTipoMovimiento($request);

        $datos = (new Gasto)->getReporte($request);
        $tipoGasto = (new TipoGastoModel)->all()
            ->mapWithKeys(function($tipoGasto) {
                return [$tipoGasto->getKey() => $tipoGasto->tipo_gasto];
            })
            ->all();

        return view('gastos.reporte', compact(
            'formCuenta', 'formAnno', 'formTipoMovimiento', 'datos', 'tipoGasto'
        ));
    }

}
