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
        $formCuenta = Cuenta::formArrayGastos();
        $formAnno = Cuenta::getFormAnno($request);
        $formTipoMovimiento = TipoMovimiento::formArray();
        $cuentaId = $request->input('cuenta_id', key($formCuenta->all()));
        $anno = $request->input('anno', key($formAnno));
        $tipoMovimientoId = $request->input('tipo_movimiento_id', key($formTipoMovimiento->all()));
        $datos = Gasto::getReporte($cuentaId, $anno, $tipoMovimientoId);

        return view('gastos.reporte', compact('formCuenta', 'formAnno', 'formTipoMovimiento', 'cuentaId', 'anno', 'tipoMovimientoId', 'datos'));
    }

    public function detalle(Request $request)
    {
        return view('gastos.detalle', [
            'movimientosMes' => Gasto::detalleMovimientosMes($request->cuenta_id, $request->anno, $request->mes, $request->tipo_gasto_id),
        ]);
    }


}
