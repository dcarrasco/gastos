<?php

namespace App\Http\Controllers\Gastos;

use \Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\TipoGasto;
use Illuminate\Http\Request;
use App\Gastos\ReporteGastos;
use App\Gastos\TipoMovimiento;
use App\Http\Controllers\Controller;

class Reporte extends Controller
{
    protected function reporte(Request $request)
    {
        return view('gastos.reporte', [
            'today' => Carbon::now(),
            'cuenta' => new Cuenta,
            'formTipoMovimiento' => TipoMovimiento::formArray(),
            'reporte' => new ReporteGastos($request->cuenta_id, $request->anno, $request->tipo_movimiento_id),
        ]);
    }

    public function detalle(Request $request)
    {
        return view('gastos.detalle', [
            'movimientosMes' => Gasto::detalleMovimientosMes($request->cuenta_id, $request->anno, $request->mes, $request->tipo_gasto_id),
        ]);
    }


}
