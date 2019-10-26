<?php

namespace App\Gastos;

use Illuminate\Support\Arr;

class Reporte
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

    public function getDato($fila = '', $columna = '', $default = '')
    {
        return Arr::get($this->reporte, "{$fila}.{$columna}", $default);
    }

    public function totalFila($fila = '')
    {
        return $this->reporte
            ->get($fila)
            ->sum();
    }

    public function countFila($fila = '')
    {
        return $this->reporte
            ->get($fila)
            ->count();
    }

    public function totalColumna($columna)
    {
        return $this->reporte
            ->map->get($columna)
            ->sum();
    }

    public function totalReporte()
    {
        return $this->reporte
            ->map->sum()
            ->sum();
    }

    public function promedioReporte()
    {
        return $this->totalReporte() / $this->reporte->map->keys()->max()->max();
    }

    public function getReporte()
    {
        return $this->reporte;
    }

    public function titulosColumnas()
    {
        return $this->titulosColumnas;
    }

    public function titulosFilas()
    {
        return $this->titulosFilas;
    }

    protected function makeReporte()
    {
        $reporte = [];

        $this->data->each(function($dato) use (&$reporte) {
            $reporte[$dato[$this->campoFila]][$dato[$this->campoColumna]] = $dato[$this->campoDato];
        });

        return collect($reporte)->map(function($fila) {
            return collect($fila);
        });
    }

    protected function makeTitulosColumnas()
    {
    }

    protected function makeTitulosFilas()
    {
    }
}
