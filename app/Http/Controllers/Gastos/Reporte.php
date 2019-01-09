<?php

namespace App\Http\Controllers\Gastos;

use \Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use App\Http\Controllers\Controller;

class Reporte extends Controller
{
    protected function reporte(Request $request)
    {
        $formCuenta = (new Cuenta)->formArrayGastos();
        $formAnno = (new Cuenta)->getFormAnno($request);
        $annoDefault = Carbon::now()->year;
        $formTipoMovimiento = (new TipoMovimiento)->formArray();

        $datos = (new Gasto)->getReporte($request);

        $tipoGasto = TipoGasto::orderBy('tipo_gasto')
            ->whereIn('id', array_get($datos, 'tipo_gasto_id', []))
            ->get()
            ->mapWithKeys(function($tipoGasto) {
                return [$tipoGasto->getKey() => $tipoGasto->tipo_gasto];
            });

        return view('gastos.reporte', compact(
            'formCuenta', 'formAnno', 'annoDefault', 'formTipoMovimiento', 'datos', 'tipoGasto'
        ));
    }

}
