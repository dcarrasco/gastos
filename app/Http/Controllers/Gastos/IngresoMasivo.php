<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Cuenta;
use App\Gastos\GastosParser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\IngresoMasivoRequest;

class IngresoMasivo extends Controller
{
    public function __construct(GastosParser $parser)
    {
        $this->parser = $parser;
    }

    public function ingresoMasivo(Request $request)
    {
        if ($request->agregar === 'agregar') {
            return $this->addGastosMasivos($request, $this->parser->procesaMasivo($request));
        }

        return view('gastos.ingresoMasivo', [
            'formCuenta' => Cuenta::selectCuentasGastos(),
            'formAnno' => Cuenta::selectAnnos(),
            'annoDefault' => Carbon::now()->year,
            'formMes' => Cuenta::selectMeses(),
            'mesDefault' => Carbon::now()->month,
            'datosMasivos' => $this->parser->procesaMasivo($request),
        ]);
    }

    protected function addGastosMasivos($request, $datosMasivos)
    {
        collect($datosMasivos)->each(function($gasto) {
            $gasto->save();
        });

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }

}
