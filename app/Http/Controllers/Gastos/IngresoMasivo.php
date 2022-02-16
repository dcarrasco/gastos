<?php

namespace App\Http\Controllers\Gastos;

use Illuminate\Http\Request;
use App\Models\Gastos\TipoGasto;
use Illuminate\Support\Collection;
use Illuminate\Contracts\View\View;
use App\Http\Controllers\Controller;
use App\Models\Gastos\GlosaTipoGasto;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Gasto\IngresoMasivoRequest;
use App\Models\Gastos\ParserMasivo\GastosParser;
use App\Models\Gastos\ParserMasivo\VisaPdfParser;
use App\Models\Gastos\ParserMasivo\VisaExcelParser;

class IngresoMasivo extends Controller
{
    protected GastosParser $parser;

    /** @var Collection<array-key, string> */
    protected $cuentas;

    /** @var class-string[] */
    protected $parsers = [
        VisaExcelParser::class,
        VisaPdfParser::class,
    ];


    public function __construct(Request $request)
    {
        $this->cuentas = $this->getParsers()
            ->map->getCuenta()
            ->pluck('cuenta', 'id');

        $this->parser = $this->getParsers()
            ->first(
                fn($parser) => (string) $parser == $request->input('parser', (string) $this->getParsers()->first())
            );
    }

    /**
     * Devuelve instancia de los parsers disponibles
     *
     * @return Collection<array-key, GastosParser>
     */
    protected function getParsers(): Collection
    {
        $parsers = collect($this->parsers)
            ->map(function ($parser) {
                /** @var GastosParser */
                $newParser = new $parser();

                return $newParser;
            });

        return $parsers->combine($parsers);
    }

    public function index(Request $request): View
    {
        return view('gastos.masivo-index', [
            'formCuenta' => $this->cuentas,
            'formParser' => $this->getParsers(),
            'datosMasivos' => $datosMasivos = $this->parser->procesaMasivo($request),
            'agregarDatosMasivos' => $this->parser->agregarDatosMasivos($request),
            'selectTiposGastos' => count($datosMasivos) ? TipoGasto::selectOptions() : [],
        ]);
    }

    protected function store(IngresoMasivoRequest $request): RedirectResponse
    {
        $this->parser->procesaMasivo($request)
            ->each->save();

        return redirect()->route('gastos.ingresoMasivo', $request->only('cuenta_id', 'anno', 'mes'));
    }

    protected function storeTipoGasto(Request $request): RedirectResponse
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
