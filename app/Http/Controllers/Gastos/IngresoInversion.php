<?php

namespace App\Http\Controllers\Gastos;

use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\Inversion;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddInversionRequest;

class IngresoInversion extends Controller
{
    public function index(Request $request)
    {
        $cuentas = Cuenta::selectCuentasInversiones();
        $tiposMovimientos = TipoMovimiento::selectOptions();
        $inversion = new Inversion(
            $request->input('cuenta_id', $cuentas->keys()->first()),
            $request->input('anno', today()->year)
        );

        return view('gastos.inversion-index', compact('cuentas', 'tiposMovimientos', 'inversion'));
    }

    public function store(AddInversionRequest $request)
    {
        Gasto::createInversion($request->validated());

        return redirect()->route('gastos.ingresoInversion', $request->only('cuenta_id', 'anno'));
    }
}
