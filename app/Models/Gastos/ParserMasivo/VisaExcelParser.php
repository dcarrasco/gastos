<?php

namespace App\Models\Gastos\ParserMasivo;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class VisaExcelParser extends GastosParser
{
    protected $descripcion = 'Excel Web';

    protected $cuentaAsociada = 2;

    protected $separadorCampos = "\t";

    protected function filtrarLineasValidas(Request $request): VisaExcelParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->filter(fn($linea) => collect(explode($this->separadorCampos, $linea))->count() == 6)
            ->filter(fn($linea) => preg_match('/[0-9]{4}/', $linea) === 1);

        return $this;
    }

    protected function getFecha(Collection $linea): Carbon
    {
        [$dia, $mes, $anno] = preg_split('/-/', trim($linea[2]));

        return Carbon::create($anno, $mes, $dia, 0, 0, 0);
    }

    protected function getSerie(Collection $linea): string
    {
        return trim($linea[0]);
    }

    protected function getGlosa(Collection $linea): string
    {
        return trim($linea[3]);
    }

    protected function getMonto(Collection $linea): int
    {
        return (int) str_replace('.', '', str_replace('$', '', trim($linea[4])));
    }
}
