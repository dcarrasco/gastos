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
        $cuenta = new Cuenta;
        $formTipoMovimiento = TipoMovimiento::formArray();

        $cuentaId = $request->input('cuenta_id', key($cuenta->selectCuentasGastos()));
        $anno = $request->input('anno', key($cuenta->selectAnnos()));
        $tipoMovimientoId = $request->input('tipo_movimiento_id', key($formTipoMovimiento->all()));

        $reporte = new ReporteGastos($cuentaId, $anno, $tipoMovimientoId);

        return view('gastos.reporte', compact('reporte', 'formTipoMovimiento', 'cuentaId', 'anno', 'tipoMovimientoId', 'cuenta'));
    }

    public function detalle(Request $request)
    {
        return view('gastos.detalle', [
            'movimientosMes' => Gasto::detalleMovimientosMes($request->cuenta_id, $request->anno, $request->mes, $request->tipo_gasto_id),
        ]);
    }


}
