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
    protected $descripcion = 'PDF Cartola';

    protected $cuentaAsociada = 2;

    protected $campos = [
        'fecha' => [2, 2],
        'serie' => [3, 4],
        'descripcion' => [5, -5],
        'monto' => [-1, -1],
    ];

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
            ->filter(fn($linea) => preg_match('/\t/', $linea) == 0)
            ->filter(fn($linea) => collect(explode(" ", $linea))->count() >= 11)
            ->filter(fn($linea) => preg_match('/[0-9]{4}/', $linea) === 1);

        return $this;
    }

    protected function procesaLineas(Request $request): VisaPdfParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->map(fn($linea) => $this->procesaLineaMasivo($request, $linea));

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
            ->filter(fn($gasto) => Gasto::where($gasto->only($camposFiltro))->get()->count() == 0);

        return $this;
    }

    protected function getTipoGasto(Request $request, Collection $linea): TipoGasto
    {
        $glosa = strtoupper($this->getGlosa($linea));

        $glosaTipoGasto = $this->glosasTipoGasto
            ->first(fn($glosaTipoGasto) => strpos($glosa, strtoupper($glosaTipoGasto->glosa)) !== false)
            ?? new GlosaTipoGasto();

        return $glosaTipoGasto->tipoGasto ?? new TipoGasto();
    }

    protected function getFecha(Collection $linea): Carbon
    {
        $fecha = explode('/', $this->getCampo('fecha', $linea));

        return (new Carbon())->create(2000 + (int)$fecha[2], $fecha[1], $fecha[0], 0, 0, 0);
    }

    protected function getSerie(Collection $linea): string
    {
        return str_replace(' ', '', $this->getCampo('serie', $linea));
    }

    protected function getGlosa(Collection $linea): string
    {
        return $this->getCampo('descripcion', $linea);
    }

    protected function getMonto(Collection $linea): int
    {
        $monto = $this->getCampo('monto', $linea);

        return (int) str_replace('.', '', str_replace('$', '', $monto));
    }

    protected function getCampo(string $campo, Collection $linea): string
    {
        return $this->getRangeCamposLinea($linea, collect($this->campos)->get($campo, []))
            ->map(fn($campo) => trim($linea->get($campo, '')))
            ->implode(' ');

    }

    protected function getRangeCamposLinea(Collection $linea, array $limites): Collection
    {
        return collect(
            range($this->getCampoLinea($linea, $limites[0]), $this->getCampoLinea($linea, $limites[1]))
        );
    }

    protected function getCampoLinea(Collection $linea, int $posicion): string
    {
        return ($posicion < 0) ? $linea->count() + $posicion : $posicion - 1;
    }
}
