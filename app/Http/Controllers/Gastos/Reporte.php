<?php

namespace App\Http\Controllers\Gastos;

use App\Gastos\Gasto;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Cuenta;
use App\Http\Controllers\Controller;
use App\OrmModel\Gastos\TipoMovimiento;

class Reporte extends Controller
{
    protected function reporte(Request $request)
    {
        $formCuenta = (new Cuenta)->getFormCuentaGastos($request);
        $formAnno = (new Cuenta)->getFormAnno($request);
        $formTipoMovimiento = (new TipoMovimiento)->getFormTipoMovimiento($request);

        $datos = (new Gasto)->getReporte($request);
        $tipoGasto = (new TipoGasto)->orderBy('tipo_gasto')->get()
            ->mapWithKeys(function($tipoGasto) {
                return [$tipoGasto->getKey() => $tipoGasto->tipo_gasto];
            })
            ->filter(function($tipoGasto, $idTipoGasto) use ($datos) {
                return in_array($idTipoGasto, array_get($datos, 'tipo_gasto_id', []));
            })
            ->all();

        return view('gastos.reporte', compact(
            'formCuenta', 'formAnno', 'formTipoMovimiento', 'datos', 'tipoGasto'
        ));
    }

}
