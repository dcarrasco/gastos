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
        $datos = (new Gasto)->getReporte($request);

        return view('gastos.reporte', [
            'formCuenta' => (new Cuenta)->formArrayGastos(),
            'formAnno' => (new Cuenta)->getFormAnno($request),
            'annoDefault' => Carbon::now()->year,
            'formTipoMovimiento' => (new TipoMovimiento)->formArray(),
            'datos' => $datos,
            'tipoGasto' => (new TipoGasto)->nombresTipoGastos($datos),
        ]);
    }

}
