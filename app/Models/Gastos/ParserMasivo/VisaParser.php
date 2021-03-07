<?php

namespace App\Models\Gastos\ParserMasivo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\TipoGasto;
use Illuminate\Support\Collection;
use App\Models\Gastos\GlosaTipoGasto;

class VisaParser extends GastosParser
{
    protected $descripcion = 'PDF';

    protected $cuentaAsociada = 2;

    public function procesaMasivo(Request $request): Collection
    {
        if (! $request->has('datos')) {
            return [];
        }

        $this->glosasTipoGasto = GlosaTipoGasto::getCuenta($request->cuenta_id);

        return collect(explode(PHP_EOL, $request->input('datos')))
            ->filter(function ($linea) {
                return $this->esLineaValida($linea);
            })
            ->map(function ($linea) use ($request) {
                return $this->procesaLineaMasivo($request, $linea);
            })
            ->filter(function ($gasto) use ($request) {
                $gastoAnterior = (new Gasto())->where([
                    'cuenta_id' => $request->cuenta_id,
                    'anno' => $request->anno,
                    'fecha' => $gasto->fecha,
                    'serie' => $gasto->serie,
                    'monto' => $gasto->monto,
                ])
                ->get()
                ->first();

                return is_null($gastoAnterior);
            });
    }


    protected function procesaLineaMasivo(Request $request, string $linea): Gasto
    {
        if (empty($linea)) {
            return null;
        }

        $linea = collect(explode(' ', $linea));
        $tipoGasto = $this->getTipoGasto($request, $linea);

        return (new Gasto())->fill([
            'cuenta_id' => $request->cuenta_id,
            'anno' => $request->anno,
            'mes' => $request->mes,
            'fecha' => $this->getFecha($linea),
            'serie' => $this->getSerie($linea),
            'glosa' => $this->getGlosa($linea),
            'tipo_gasto_id' => $tipoGasto->id,
            'tipo_movimiento_id' => optional($tipoGasto->tipoMovimiento)->id,
            'monto' => (int) str_replace('.', '', str_replace('$', '', $linea->last())),
            'usuario_id' => auth()->id(),
        ]);
    }

    protected function getTipoGasto(Request $request, Collection $linea): TipoGasto
    {
        $glosa = $this->getGlosa($linea);

        $glosaTipoGasto = $this->glosasTipoGasto
            ->first(function ($glosaTipoGasto) use ($glosa) {
                return strpos(strtoupper($glosa), strtoupper($glosaTipoGasto->glosa)) !== false;
            })
            ?? new GlosaTipoGasto();

        return $glosaTipoGasto->tipoGasto ?? new TipoGasto();
    }



    protected function getIndexFecha(Collection $linea): int
    {
        return 1;
    }

    protected function getFecha(Collection $linea): Carbon
    {
        $fecha = $linea->get($this->getIndexFecha($linea));

        return (new Carbon())->create(
            2000 + (int)substr($fecha, 6, 2),
            substr($fecha, 3, 2),
            substr($fecha, 0, 2),
            0,
            0,
            0
        );
    }

    protected function esLineaValida(string $linea): bool
    {
        return preg_match('/[0-9][0-9]\/[0-9][0-9]\/[0-9][0-9]/', $linea) === 1;
    }

    protected function getIndexSerie(Collection $linea)
    {
        $indexFecha = $this->getIndexFecha($linea);
        $serie = $linea->get($indexFecha + 1) . $linea->get($indexFecha + 2);

        if (preg_match('/^[0-9]{12}$/', $serie) === 1) {
            return range($indexFecha + 1, $indexFecha + 2);
        }

        return range($indexFecha + 1, $indexFecha + 1);
    }

    protected function getSerie(Collection $linea): string
    {
        return $linea->only($this->getIndexSerie($linea))->implode('');
    }

    protected function getGlosa($linea = []): string
    {
        $glosa = '';
        $indexMonto = 3;

        while ($linea[$indexMonto] != '$') {
            $glosa .= $linea[$indexMonto] . ' ';
            $indexMonto++;
        }

        return trim($glosa);
    }

    protected function montosConSigno(Collection $linea): bool
    {
        return strpos($linea->last(), '$') !== false;
    }
}
