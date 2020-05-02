<?php

namespace App\Http\Controllers\Gastos;

use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use Illuminate\Http\Request;
use App\Gastos\ReporteGastos;
use App\Gastos\TipoMovimiento;
use App\Http\Controllers\Controller;

class Reporte extends Controller
{
    protected function index(Request $request)
    {
        $cuentas = Cuenta::selectCuentasGastos();
        $tiposMovimientos = TipoMovimiento::selectOptions();

        $reporte = new ReporteGastos(
            $request->input('cuenta_id', $cuentas->keys()->first()),
            $request->input('anno', today()->year),
            $request->input('tipo_movimiento_id', $tiposMovimientos->keys()->first())
        );

        return view('gastos.reporte.index', compact('cuentas', 'tiposMovimientos', 'reporte'));
    }

    public function show(Request $request)
    {
        return view('gastos.reporte.show', [
            'movimientosMes' => Gasto::detalleMovimientosMes(
                $request->cuenta_id,
                $request->anno,
                $request->mes,
                $request->tipo_gasto_id
            ),
        ]);
    }
}
