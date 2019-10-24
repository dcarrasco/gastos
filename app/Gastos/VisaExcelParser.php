<?php

namespace App\Gastos;

use Carbon\Carbon;
use App\Gastos\Gasto;
use App\Gastos\TipoGasto;
use Illuminate\Support\Arr;
use App\Gastos\GastosParser;
use Illuminate\Http\Request;
use App\Gastos\GlosaTipoGasto;
use Illuminate\Support\Collection;
use App\Http\Controllers\Gastos\TipoGastoModel;

class VisaExcelParser implements GastosParser
{
    protected $glosasTipoGasto = null;


    public function procesaMasivo(Request $request)
    {
        if (! $request->has('datos')) {
            return [];
        }

        return collect(explode(PHP_EOL, $request->input('datos')))
            ->filter(function($linea) {
                return $this->esLineaValida($linea);
            })
            ->map(function($linea) use ($request) {
                return $this->procesaLineaMasivo($request, $linea);
            })
            ->filter(function ($gasto) use ($request) {
                $gastoAnterior = Gasto::where(
                        Arr::only($gasto->toArray(), ['cuenta_id', 'anno', 'fecha', 'serie', 'monto'])
                    )->get()
                    ->first();

                return is_null($gastoAnterior);
            });
    }

    protected function getTipoGasto(Request $request, $linea = '')
    {
        if (is_null($this->glosasTipoGasto)) {
            $this->glosasTipoGasto = GlosaTipoGasto::getCuenta($request->cuenta_id);
        }

        $glosa = $this->getGlosa($linea);

        $glosaTipoGasto = $this->glosasTipoGasto
            ->first(function($glosaTipoGasto) use ($glosa) {
                return strpos(strtoupper($glosa), strtoupper($glosaTipoGasto->glosa)) !== false;
            });

        return $glosaTipoGasto->tipoGasto;
    }

    protected function procesaLineaMasivo(Request $request, $linea = '')
    {
        if (empty($linea)) {
            return null;
        }

        $linea = collect(explode("\t", $linea));

        $tipoGasto = $this->getTipoGasto($request, $linea);

        return new Gasto([
            'cuenta_id' => $request->cuenta_id,
            'anno' => $request->anno,
            'mes' => $request->mes,
            'fecha' => $this->getFecha($linea),
            'serie' => $this->getSerie($linea),
            'glosa' => $this->getGlosa($linea),
            'tipo_gasto_id' => $tipoGasto->id,
            'tipo_movimiento_id' => optional($tipoGasto->tipoMovimiento)->id,
            'monto' => $this->getMonto($linea),
            'usuario_id' => auth()->id(),
        ]);
    }

    protected function getIndexFecha(Collection $linea)
    {
        return $linea->filter(function($item) {
                return preg_match('/^[0-9]{2}\/[0-9]{2}\/[0-9]{2}/', $item) === 1;
            })
            ->map(function($item, $key) {
                return $key;
            })
            ->first();
    }

    protected function getFecha(Collection $linea)
    {
        $fecha = $linea[2];

        return Carbon::create(substr($fecha, 6, 4), substr($fecha, 3, 2), substr($fecha, 0, 2), 0, 0, 0);
    }

    protected function esLineaValida($linea = '')
    {
        return preg_match('/[0-9]{4}/', $linea) === 1;
    }

    protected function getIndexSerie(Collection $linea)
    {
        $indexFecha = $this->getIndexFecha($linea);
        $serie = $linea->get($indexFecha+1).$linea->get($indexFecha+2);

        if (preg_match('/^[0-9]{12}$/', $serie) === 1) {
            return range($indexFecha + 1, $indexFecha + 2);
        }

        return range($indexFecha + 1, $indexFecha + 1);
    }

    protected function getSerie(Collection $linea)
    {
        return $linea[0];
    }

    protected function getGlosa($linea = [])
    {
        return trim($linea[3]);
    }

    protected function getMonto($linea = [])
    {
        return (int) str_replace('.', '', str_replace('$', '', trim($linea[4])));
    }

    protected function montosConSigno(Collection $linea)
    {
        return strpos($linea->last(), '$') !== false;
    }
}
