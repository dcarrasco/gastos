<?php

namespace App\Gastos;

use App\Gastos\Gasto;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;

class ReporteGastos
{
    protected $data;
    protected $reporte = [];
    protected $titulosColumnas = [];
    protected $titulosFilas = [];

    protected $campoColumna = 'mes';
    protected $campoFila = 'tipo_gasto_id';
    protected $campoDato = 'sum_monto';

    public function __construct($cuentaId, $anno, $tipoMovimientoId)
    {
        $this->data = Gasto::getDataReporte($cuentaId, $anno, $tipoMovimientoId);
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
        return $this->reporte->get($fila)->sum();
    }

    public function countFila($fila = '')
    {
        return $this->reporte->get($fila)->count();
    }

    public function totalColumna($columna)
    {
        return $this->reporte->map(function($elem) use ($columna) {
            return $elem->get($columna);
        })->sum();
    }

    public function totalReporte()
    {
        return $this->reporte->map->sum()->sum();
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
        return collect($reporte)->map(function($elem) {return collect($elem);});
    }

    protected function makeTitulosColumnas()
    {
        return collect(range(1,12))->combine(range(1,12))->map(function($mes) {
            return trans('fechas.'.Carbon::create(2000, $mes, 1)->format('F'));
        });
    }

    protected function makeTitulosFilas()
    {
        return $this->data
            ->map->tipoGasto
            ->unique()
            ->sortBy('tipo_gasto')
            ->pluck('tipo_gasto', 'id');
    }



}

