<?php

namespace App\Http\Controllers\Gastos;

use Illuminate\Http\Request;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\Cuenta;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Gastos\ReporteGastos;
use App\Models\Gastos\TipoMovimiento;

class Reporte extends Controller
{
    protected function index(Request $request): View
    {
        $cuentas = Cuenta::selectCuentasGastos();
        $tiposMovimientos = TipoMovimiento::selectOptions();

        $reporte = new ReporteGastos(
            $request->input('cuenta_id', $cuentas->keys()->first()),
            $request->input('anno', today()->year),
            $request->input('tipo_movimiento_id', $tiposMovimientos->keys()->first())
        );

        return view('gastos.reporte-index', compact('cuentas', 'tiposMovimientos', 'reporte'));
    }

    public function show(Request $request): View
    {
        return view('gastos.reporte-show', [
            'movimientosMes' => Gasto::detalleMovimientosMes(
                $request->cuenta_id,
                $request->anno,
                $request->mes,
                $request->tipo_gasto_id
            ),
        ]);
    }
}
