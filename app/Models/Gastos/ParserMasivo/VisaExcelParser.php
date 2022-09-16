<?php

namespace App\Models\Gastos\ParserMasivo;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class VisaExcelParser extends GastosParser
{
    protected string $descripcion = 'Excel Web';

    protected int $cuentaAsociada = 2;

    protected string $separadorCampos = "\t";

    protected function filtrarLineasValidas(Request $request): VisaExcelParser
    {
        $this->datosMasivos = $this->datosMasivos
            ->filter(fn ($linea) => collect(explode($this->separadorCampos, $linea))->count() == 6)
            ->filter(fn ($linea) => preg_match('/[0-9]{4}/', $linea) === 1);

        return $this;
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getFecha(Collection $linea): Carbon
    {
        return Carbon::createFromFormat('d-m-Y H:i:s', trim($linea[2]).' 00:00:00');
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getSerie(Collection $linea): string
    {
        return $linea[0];
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getGlosa(Collection $linea): string
    {
        return $linea[3];
    }

    /** @param  Collection<array-key, string>  $linea */
    protected function getMonto(Collection $linea): int
    {
        return (int) str_replace(['.', '$'], '', $linea[4]);
    }
}
