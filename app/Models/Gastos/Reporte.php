<?php

namespace App\Models\Gastos;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

abstract class Reporte
{
    /** @var Collection<array-key, Gasto> */
    protected $data;

    /** @var Collection<array-key, Collection<(int|string), mixed>> */
    protected $reporte;

    /** @var Collection<array-key, string> */
    protected $titulosColumnas;

    /** @var Collection<array-key, string> */
    protected $titulosFilas;

    protected string $campoColumna = '';
    protected string $campoFila = '';
    protected string $campoDato = '';

    /** @var Callable[] */
    protected array $valueFormats = [];


    public function __construct()
    {
        $this->reporte = $this->makeReporte();
        $this->titulosColumnas = $this->makeTitulosColumnas();
        $this->titulosFilas = $this->makeTitulosFilas();

        $this->valueFormats = [
            'dato' => fn($valor) => fmtMonto($valor),
        ];
    }

    public function isEmpty(): bool
    {
        return $this->reporte->isEmpty();
    }

    public function getFormatted(string $format, mixed $value): HtmlString
    {
        $formatFunction = Arr::get($this->valueFormats, $format, fn($value) => $value);

        return new HtmlString($formatFunction($value));
    }

    public function getDato(string $fila, string $columna, string $default = ''): int
    {
        return Arr::get($this->reporte, "{$fila}.{$columna}", $default);
    }

    public function formattedDato(string $fila, string $columna): HtmlString
    {
        return $this->getFormatted('dato', $this->getDato($fila, $columna, ''));
    }

    public function formattedTotalFila(string $fila): HtmlString
    {
        return $this->getFormatted('dato', $this->totalFila($fila));
    }

    public function formattedPromedioFila(string $fila): HtmlString
    {
        return $this->getFormatted('dato', $this->promedioFila($fila));
    }

    public function formattedTotalColumna(string $fila): HtmlString
    {
        return $this->getFormatted('dato', $this->totalColumna($fila));
    }

    public function formattedTotalReporte(): HtmlString
    {
        return $this->getFormatted('dato', $this->totalReporte());
    }

    public function formattedPromedioReporte(): HtmlString
    {
        return $this->getFormatted('dato', $this->promedioReporte());
    }

    public function totalFila(string $fila): int
    {
        return $this->reporte
            ->get($fila)
            ->sum();
    }

    public function countFila(string $fila): int
    {
        return $this->reporte
            ->get($fila)
            ->count();
    }

    public function promedioFila(string $fila): int
    {
        return $this->totalFila($fila) / $this->countFila($fila);
    }


    public function totalColumna(string $columna): int
    {
        return $this->reporte
            ->map->get($columna)
            ->sum();
    }

    public function totalReporte(): int
    {
        return $this->reporte
            ->map->sum()
            ->sum();
    }

    public function promedioReporte(): int
    {
        return (int) ($this->totalReporte() / $this->reporte->map->keys()->max()->max());
    }

    /** @return Collection<array-key, Collection<(int|string), mixed>> */
    public function getReporte(): Collection
    {
        return $this->reporte;
    }

    /** @return Collection<array-key, string> */
    public function titulosColumnas(): Collection
    {
        return $this->titulosColumnas;
    }

    /** @return Collection<array-key, string> */
    public function titulosFilas(): Collection
    {
        return $this->titulosFilas;
    }

    /** @return Collection<array-key, Collection<(int|string), mixed>> */
    protected function makeReporte(): Collection
    {
        $reporte = [];

        $this->data->each(function ($dato) use (&$reporte) {
            $reporte[$dato[$this->campoFila]][$dato[$this->campoColumna]] = $dato[$this->campoDato];
        });

        return collect($reporte)
            ->map(fn($fila) => collect($fila));
    }

    /** @return Collection<array-key, string> */
    abstract protected function makeTitulosColumnas(): Collection;

    /** @return Collection<array-key, string> */
    abstract protected function makeTitulosFilas(): Collection;
}
