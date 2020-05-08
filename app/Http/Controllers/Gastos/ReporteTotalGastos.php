<?php

namespace App\Http\Controllers\Gastos;

use App\Gastos\Gasto;
use Illuminate\Http\Request;
use App\Gastos\ReporteGastosTotales;
use App\Http\Controllers\Controller;

class ReporteTotalGastos extends Controller
{
    protected function index(Request $request)
    {
        return view('gastos.reporte-total-gastos-index', [
            'reporte' => new ReporteGastosTotales($request->input('anno', today()->year)),
        ]);
    }

    public function show(Request $request)
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
