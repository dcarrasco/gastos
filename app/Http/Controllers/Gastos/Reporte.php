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
        return view('gastos.reporte', [
            'formCuenta' => Cuenta::formArrayGastos(),
            'formAnno' => Cuenta::getFormAnno($request),
            'formTipoMovimiento' => TipoMovimiento::formArray(),
            'datos' => Gasto::getReporte($request->cuenta_id, $request->anno, $request->tipo_movimiento_id),
        ]);
    }

    public function detalle(Request $request)
    {
        return view('gastos.detalle', [
            'movimientosMes' => Gasto::detalleMovimientosMes($request->cuenta_id, $request->anno, $request->mes, $request->tipo_gasto_id),
        ]);
    }


}
