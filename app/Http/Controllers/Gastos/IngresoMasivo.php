<?php

namespace App\Http\Controllers\Gastos;

use Illuminate\Http\Request;
use App\Models\Gastos\TipoGasto;
use App\Http\Controllers\Controller;
use App\Models\Gastos\GlosaTipoGasto;
use App\Models\Gastos\ParserMasivo\VisaPdfParser;
use App\Http\Requests\Gasto\IngresoMasivoRequest;
use App\Models\Gastos\ParserMasivo\VisaExcelParser;

class IngresoMasivo extends Controller
{
    protected $parser = null;

    protected $cuentas = null;

    protected $parsers = [
        VisaExcelParser::class,
        VisaPdfParser::class,
    ];


    public function __construct(Request $request)
    {
        $this->parsers = collect($this->parsers)
            ->map(fn($parser) => new $parser());

        $this->parsers = $this->parsers->combine($this->parsers);

        $this->cuentas = $this->parsers
            ->map->getCuenta()
            ->pluck('cuenta', 'id');

        $this->parser = $this->parsers->first(fn($parser) =>
            (string) $parser == $request->input('parser',
                    (string) $this->parsers
                        ->first->hasCuenta($request->input('cuenta_id', $this->cuentas->keys()->first()))
                ));
    }

    public function index(Request $request)
    {
        return view('gastos.masivo-index', [
            'formCuenta' => $this->cuentas,
            'formParser' => $this->parsers,
            'datosMasivos' => $datosMasivos = $this->parser->procesaMasivo($request),
            'agregarDatosMasivos' => $this->parser->agregarDatosMasivos($request),
            'selectTiposGastos' => count($datosMasivos) ? TipoGasto::selectOptions() : [],
        ])->withErrors($this->parser->getParserError());
    }

    protected function store(IngresoMasivoRequest $request)
    {
        $this->parser->procesaMasivo($request)
            ->each->save();

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }

    protected function storeTipoGasto(Request $request)
    {
        GlosaTipoGasto::create([
            'cuenta_id' => $request->input('cuenta_id'),
            'glosa' => trim(str_replace('COMPRAS', '', $request->input('glosa_tipo_gasto'))),
            'tipo_gasto_id' => $request->input('tipo_gasto_id'),
        ]);

        return redirect()->route('gastos.ingresoMasivo')
            ->withInput($request->only('cuenta_id', 'anno', 'mes', 'datos'));
    }
}
