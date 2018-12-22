<?php

namespace App\Http\Controllers\Gastos;

use App\Gastos\Gasto;
use App\Gastos\Inversion;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Cuenta;
use App\Http\Controllers\Controller;
use App\OrmModel\Gastos\TipoMovimiento;
use App\Http\Requests\Gasto\AddInversionRequest;

class IngresoInversion extends Controller
{
    public function formularioIngreso(Request $request)
    {
        $formCuenta = (new Cuenta)->getFormCuentaInversiones($request);
        $formAnno = (new Cuenta)->getFormAnno($request);
        $formTipoMovimiento = (new TipoMovimiento)->getFormTipoMovimiento($request, ['class' => 'form-control form-control-sm']);

        $inversion = new Inversion($request);
        $rentabilidadesAnual = $inversion->getAllRentabilidadesAnual();

        return view('gastos.showinversion', compact(
            'formCuenta', 'formAnno', 'formTipoMovimiento', 'inversion', 'rentabilidadesAnual'
        ));
    }

    public function addInversion(AddInversionRequest $request)
    {
        $gasto = (new Gasto)->fill($request->all());
        $gasto->mes = $gasto->fecha->month;
        $gasto->usuario_id = auth()->id();
        $gasto->tipo_gasto_id = 0;
        $gasto->save();

        return redirect()->route('gastos.ingresoInversion', [
            'cuenta_id' => $request->input('cuenta_id'),
            'anno' => $request->input('anno'),
        ]);
    }

}
