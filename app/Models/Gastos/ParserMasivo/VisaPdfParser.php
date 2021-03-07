<?php

namespace App\Models\Gastos\ParserMasivo;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Gastos\Gasto;
use App\Models\Gastos\TipoGasto;
use Illuminate\Support\Collection;
use App\Models\Gastos\GlosaTipoGasto;

class VisaPdfParser extends GastosParser
{
    protected $descripcion = 'PDF';

    protected $cuentaAsociada = 2;

    public function procesaMasivo(Request $request): Collection
    {
        if (! $request->has('datos')) {
            return [];
        }

        $this->glosasTipoGasto = GlosaTipoGasto::getCuenta($request->cuenta_id);

        $this->datosMasivos = $this->requestDatosMasivos($request)
            ->filtrarLineasValidas($request)
            ->procesaLineas($request)
            ->filtraLineasExistentes($request)
            ->getDatosMasivos();

        return $this->getDatosMasivos();
    }

    protected function requestDatosMasivos(Request $request): VisaPdfParser
    {
        $this->datosMasivos = collect(explode(PHP_EOL, $request->datos));

        return $this;
    }

    protected function filtrarLineasValidas(Request $request): VisaPdfParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->filter(function ($linea) {
                return preg_match('/\t/', $linea) == 0;
            })
            ->filter(function ($linea) {
                return collect(explode(" ", $linea))->count() >= 11;
            })
            ->filter(function ($linea) {
                return preg_match('/[0-9]{4}/', $linea) === 1;
            });

        return $this;
    }

    protected function procesaLineas(Request $request): VisaPdfParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->map(function ($linea) use ($request) {
                return $this->procesaLineaMasivo($request, $linea);
            });

        return $this;
    }

    protected function procesaLineaMasivo(Request $request, string $linea): Gasto
    {
        $linea = collect(explode(' ', $linea));
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

    protected function filtraLineasExistentes(Request $request): VisaPdfParser
    {
        $camposFiltro = ['cuenta_id', 'anno', 'fecha', 'serie', 'monto'];

        $this->datosMasivos = $this->datosMasivos
            ->filter(function ($gasto) use ($request, $camposFiltro) {
                return Gasto::where($gasto->only($camposFiltro))->get()->count() == 0;
            });

        return $this;
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
        $fecha = explode('/', $linea->get($this->getIndexFecha($linea)));

        return (new Carbon())->create(2000 + (int)$fecha[2], $fecha[1], $fecha[0], 0, 0, 0);
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

    protected function getMonto(Collection $linea): int
    {
        return (int) str_replace('.', '', str_replace('$', '', $linea->last()));
    }

    protected function montosConSigno(Collection $linea): bool
    {
        return strpos($linea->last(), '$') !== false;
    }
}
