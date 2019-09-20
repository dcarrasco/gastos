<?php

namespace App\Http\Controllers\Gastos;

use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\Inversion;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddInversionRequest;

class IngresoInversion extends Controller
{
    public function formularioIngreso(Request $request)
    {
        $today = Carbon::now();

        return view('gastos.showinversion', [
            'formCuenta' => Cuenta::selectCuentasInversiones(),
            'today' => $today,
            'formTipoMovimiento' => TipoMovimiento::formArray(),
            'inversion' => new Inversion(
                $request->input('cuenta_id', key(Cuenta::selectCuentasInversiones())),
                $request->input('anno', $today->year)
            ),
        ]);
    }

    public function addInversion(AddInversionRequest $request)
    {
        Gasto::create(array_merge($request->validated(), [
            'mes' => Carbon::create($request->fecha)->month,
            'usuario_id' => auth()->id(),
            'tipo_gasto_id' => 0,
        ]));

        return redirect()->route('gastos.ingresoInversion', $request->only('cuenta_id', 'anno'));
    }

}
