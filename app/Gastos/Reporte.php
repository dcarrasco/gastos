<?php

namespace App\Gastos;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

abstract class Reporte
{
    protected $data;
    protected $reporte = [];
    protected $titulosColumnas = [];
    protected $titulosFilas = [];

    protected $campoColumna = '';
    protected $campoFila = '';
    protected $campoDato = '';


    public function __construct()
    {
        $this->reporte = $this->makeReporte();
        $this->titulosColumnas = $this->makeTitulosColumnas();
        $this->titulosFilas = $this->makeTitulosFilas();
    }

    public function isEmpty(): bool
    {
        return $this->reporte->isEmpty();
    }

    public function getDato(string $fila, string $columna, string $default = ''): int
    {
        return Arr::get($this->reporte, "{$fila}.{$columna}", $default);
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
        return $this->totalReporte() / $this->reporte->map->keys()->max()->max();
    }

    public function getReporte(): Collection
    {
        return $this->reporte;
    }

    public function titulosColumnas(): Collection
    {
        return $this->titulosColumnas;
    }

    public function titulosFilas(): Collection
    {
        return $this->titulosFilas;
    }

    protected function makeReporte(): Collection
    {
        $reporte = [];

        $this->data->each(function ($dato) use (&$reporte) {
            $reporte[$dato[$this->campoFila]][$dato[$this->campoColumna]] = $dato[$this->campoDato];
        });

        return collect($reporte)->map(function ($fila) {
            return collect($fila);
        });
    }

    abstract protected function makeTitulosColumnas(): Collection;

    abstract protected function makeTitulosFilas(): Collection;
}
