<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Cuenta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Gastos\ParserMasivo\NullParser;
use App\Gastos\ParserMasivo\GastosParser;
use App\Http\Requests\Gasto\IngresoMasivoRequest;

class IngresoMasivo extends Controller
{
    protected $parser = null;


    public function __construct(GastosParser $parser)
    {
        $this->parser = $parser;
    }

    protected function getParserError()
    {
        return get_class($this->parser) == NullParser::class
            ? ['ParserError' => 'No se puede ingresar masivo esta cuenta']
            : [];
    }

    public function ingresoMasivo(Request $request)
    {
        if ($request->agregar === 'agregar') {
            return $this->addGastosMasivos($request, $this->parser->procesaMasivo($request));
        }

        return view('gastos.ingresoMasivo', [
            'today' => Carbon::now(),
            'formCuenta' => Cuenta::selectCuentasGastos(),
            'datosMasivos' => $this->parser->procesaMasivo($request),
        ])->withErrors($this->getParserError());
    }

    protected function addGastosMasivos($request, $datosMasivos)
    {
        collect($datosMasivos)->each(function($gasto) {
            $gasto->save();
        });

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }

}
