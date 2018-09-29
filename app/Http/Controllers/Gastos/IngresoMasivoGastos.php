<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\VisaParser;
use Illuminate\Http\Request;
use App\OrmModel\Gastos\Cuenta;
use App\Http\Controllers\Controller;

class IngresoMasivoGastos extends Controller
{
    public function ingresoMasivo(Request $request)
    {
        $formCuenta = (new Cuenta)->getFormCuenta($request);
        $formAnno = (new Cuenta)->getFormAnno($request);
        $formMes = (new Cuenta)->getFormMes($request);
        $datosMasivos = (new VisaParser)->procesaMasivo($request);

        if ($request->agregar === 'agregar') {
            return $this->addGastosMasivos($request, $datosMasivos);
        }

        return view('gastos.ingresoMasivo', compact(
            'formCuenta', 'formAnno', 'formMes', 'datosMasivos'
        ));
    }

    protected function addGastosMasivos(Request $request, $datosMasivos)
    {
        collect($datosMasivos)->each(function($gasto) {
            $gasto->save();
        });

        return redirect()->route('gastos.ingresoMasivo', [
            'cuenta_id' => $request->input('cuenta_id'),
            'anno' => $request->input('anno'),
            'mes' => $request->input('mes'),
        ]);
    }

}
