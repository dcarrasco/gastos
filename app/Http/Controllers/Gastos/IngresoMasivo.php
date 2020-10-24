<?php

namespace App\Http\Controllers\Gastos;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Gasto\IngresoMasivoRequest;
use App\Models\Gastos\ParserMasivo\VisaExcelParser;

class IngresoMasivo extends Controller
{
    protected $parser = null;

    protected $cuentas = null;

    protected $parsers = [
        VisaExcelParser::class,
    ];


    public function __construct(Request $request)
    {
        $this->parsers = collect($this->parsers)
            ->map(fn($parser) => new $parser);

        $this->cuentas = $this->parsers
            ->map->getCuenta()
            ->pluck('cuenta', 'id');

        $this->parser = $this->parsers
            ->first->hasCuenta($request->input('cuenta_id', $this->cuentas->keys()->first()));
    }

    public function index(Request $request)
    {
        return view('gastos.masivo-index', [
            'formCuenta' => $this->cuentas,
            'datosMasivos' => $this->parser->procesaMasivo($request),
            'agregarDatosMasivos' => $this->parser->agregarDatosMasivos($request),
        ])->withErrors($this->parser->getParserError());
    }

    protected function store(IngresoMasivoRequest $request)
    {
        $this->parser->procesaMasivo($request)
            ->each->save();

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }
}
