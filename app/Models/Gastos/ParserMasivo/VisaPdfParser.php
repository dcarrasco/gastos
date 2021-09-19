<?php

namespace App\Models\Gastos\ParserMasivo;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class VisaPdfParser extends GastosParser
{
    protected string $descripcion = 'PDF Cartola';

    protected int $cuentaAsociada = 2;

    protected string $separadorCampos = ' ';

    /** @var array[] */
    protected array $campos = [
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

    protected function getFecha(Collection $linea): Carbon
    {
        return Carbon::createFromFormat("d/m/y H:i:s", $this->getCampo('fecha', $linea) . ' 00:00:00');
    }

    protected function getSerie(Collection $linea): string
    {
        return $this->getCampo('serie', $linea);
    }

    protected function getGlosa(Collection $linea): string
    {
        return $this->getCampo('descripcion', $linea);
    }

    protected function getMonto(Collection $linea): int
    {
        return (int) str_replace(['.', '$'], '', $this->getCampo('monto', $linea));
    }

    protected function getCampo(string $campo, Collection $linea): string
    {
        return $linea->only($this->getRangeCamposLinea($linea, $this->campos[$campo]))
            ->implode(' ');
    }

    /**
     * Devuelve rango para recuperar campo
     *
     * @param Collection $linea
     * @param int[] $limites
     * @return int[]
     */
    protected function getRangeCamposLinea(Collection $linea, array $limites): array
    {
        [$desde, $hasta] = $limites;

        return range($this->posicionLinea($desde, $linea), $this->posicionLinea($hasta, $linea));
    }

    protected function posicionLinea(int $posicion, Collection $linea): int
    {
        return ($posicion < 0) ? $linea->count() + $posicion : $posicion - 1;
    }
}
