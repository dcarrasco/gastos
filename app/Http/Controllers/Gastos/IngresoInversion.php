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
        $formCuenta = (new Cuenta)->formArrayInversiones($request);
        $formAnno = (new Cuenta)->getFormAnno($request);
        $annoDefault = Carbon::now()->year;
        $formTipoMovimiento = (new TipoMovimiento)->formArray(['class' => 'form-control form-control-sm']);

        $inversion = new Inversion($request);
        $rentabilidadesAnual = $inversion->getAllRentabilidadesAnual();

        return view('gastos.showinversion', compact(
            'formCuenta', 'formAnno', 'annoDefault', 'formTipoMovimiento', 'inversion', 'rentabilidadesAnual'
        ));
    }

    public function addInversion(AddInversionRequest $request)
    {
        $gasto = (new Gasto)->fill($request->all());
        $gasto->mes = $gasto->fecha->month;
        $gasto->usuario_id = auth()->id();
        $gasto->tipo_gasto_id = 0;
        $gasto->save();

        return redirect()->route('gastos.ingresoInversion', $request->only('cuenta_id', 'anno'));
    }

}
