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
        $currentLocale = setlocale(LC_TIME, 'es-ES');

        $datosMasivos = $this->parser->procesaMasivo($request);
        $agregarDatosMasivos = $datosMasivos->count() == $datosMasivos->filter(function ($gasto) {
            return $gasto->tipo_gasto_id !== null;
        })->count();

        return view('gastos.masivo.index', [
            'today' => Carbon::now(),
            'formCuenta' => Cuenta::selectCuentasGastos(),
            'datosMasivos' => $datosMasivos,
            'agregarDatosMasivos' => $agregarDatosMasivos,
        ])->withErrors($this->getParserError());
    }

    protected function addGastosMasivos(IngresoMasivoRequest $request)
    {
        $this->parser->procesaMasivo($request)
            ->each->save();

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }
}
