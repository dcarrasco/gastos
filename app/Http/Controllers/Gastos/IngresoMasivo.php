<?php

namespace App\Http\Controllers\Gastos;

use Carbon\Carbon;
use App\Gastos\Cuenta;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Http\Controllers\Controller;
use App\Gastos\ParserMasivo\NullParser;
use App\Gastos\ParserMasivo\VisaExcelParser;
use App\Http\Requests\Gasto\IngresoMasivoRequest;

class IngresoMasivo extends Controller
{
    protected $parser = null;

    protected $cuentas = null;

    protected $parsers = [
        VisaExcelParser::class,
    ];


    public function __construct(Request $request)
    {
        $this->parsers = arrayToInstanceCollection($this->parsers);

        $this->cuentas = $this->parsers
            ->map->getCuenta()
            ->pluck('cuenta', 'id');

        $this->parser = $this->parsers
            ->first->hasCuenta($request->input('cuenta_id', $this->cuentas->keys()->first()));
    }

    public function index(Request $request)
    {
        $currentLocale = setlocale(LC_TIME, 'es-ES');

        $datosMasivos = $this->parser->procesaMasivo($request);
        $agregarDatosMasivos = $datosMasivos->count() == $datosMasivos->filter(function ($gasto) {
            return $gasto->tipo_gasto_id !== null;
        })->count();

        return view('gastos.masivo.index', [
            'today' => Carbon::now(),
            'formCuenta' => $this->cuentas,
            'datosMasivos' => $datosMasivos,
            'agregarDatosMasivos' => $agregarDatosMasivos,
        ])->withErrors($this->parser->getParserError());
    }

    protected function store(IngresoMasivoRequest $request)
    {
        $this->parser->procesaMasivo($request)
            ->each->save();

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }
}
