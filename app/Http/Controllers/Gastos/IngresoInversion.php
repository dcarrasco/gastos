<?php

namespace App\Http\Controllers\Gastos;

use \Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\Cuenta;
use App\Gastos\Inversion;
use Illuminate\Http\Request;
use App\Gastos\TipoMovimiento;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\AddInversionRequest;

class IngresoInversion extends Controller
{
    public function formularioIngreso(Request $request)
    {
        $inversion = new Inversion($request);

        return view('gastos.showinversion', [
            'formCuenta' => Cuenta::formArrayInversiones($request),
            'formAnno' => Cuenta::getFormAnno($request),
            'annoDefault' => Carbon::now()->year,
            'formTipoMovimiento' => TipoMovimiento::formArray(),
            'inversion' => $inversion,
            'rentabilidadesAnual' => $inversion->getAllRentabilidadesAnual(),
        ]);
    }

    public function addInversion(AddInversionRequest $request)
    {
        $gasto = new Gasto($request->all());

        $gasto->mes = $gasto->fecha->month;
        $gasto->usuario_id = auth()->id();
        $gasto->tipo_gasto_id = 0;

        $gasto->save();

        return redirect()->route('gastos.ingresoInversion', $request->only('cuenta_id', 'anno'));
    }

}
