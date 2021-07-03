<?php

namespace App\Models\Gastos\ParserMasivo;

use App\Models\Gastos\Gasto;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class VisaPdfParser extends GastosParser
{
    protected $descripcion = 'PDF Cartola';

    protected $cuentaAsociada = 2;

    protected $separadorCampos = ' ';

    protected $campos = [
        'fecha' => [2, 2],
        'serie' => [3, 4],
        'descripcion' => [5, -5],
        'monto' => [-1, -1],
    ];

    protected function filtrarLineasValidas(Request $request): VisaPdfParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->filter(fn($linea) => preg_match('/\t/', $linea) == 0)
            ->filter(fn($linea) => collect(explode(" ", $linea))->count() >= 11)
            ->filter(fn($linea) => preg_match('/[0-9]{4}/', $linea) === 1);

        return $this;
    }

    protected function filtraLineasExistentes(Request $request): VisaPdfParser
    {
        $camposFiltro = ['cuenta_id', 'anno', 'fecha', 'serie', 'monto'];

        $this->datosMasivos = $this->datosMasivos
            ->filter(fn($gasto) => Gasto::where($gasto->only($camposFiltro))->get()->count() == 0);

        return $this;
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
