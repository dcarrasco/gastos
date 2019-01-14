<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Cuenta;
use App\Gastos\VisaParser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\IngresoMasivoRequest;

class IngresoMasivo extends Controller
{
    public function ingresoMasivo(Request $request)
    {
        $formCuenta = Cuenta::formArrayGastos();
        $formAnno = Cuenta::getFormAnno();
        $annoDefault = Carbon::now()->year;
        $formMes = Cuenta::getFormMes();
        $mesDefault = Carbon::now()->month;
        $datosMasivos = (new VisaParser)->procesaMasivo($request);

        if ($request->agregar === 'agregar') {
            return $this->addGastosMasivos($request, $datosMasivos);
        }

        return view('gastos.ingresoMasivo', compact(
            'formCuenta', 'formAnno', 'annoDefault', 'formMes', 'mesDefault', 'datosMasivos'
        ));
    }

    protected function addGastosMasivos(IngresoMasivoRequest $request, $datosMasivos)
    {
        collect($datosMasivos)->each(function($gasto) {
            $gasto->save();
        });

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }

}
