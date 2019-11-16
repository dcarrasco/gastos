<?php

namespace App\Gastos;

use App\Gastos\Gasto;

class Inversion
{
    protected $movimientos;

    protected $saldos;


    public function __construct($cuenta, $anno)
    {
        $this->movimientos = Gasto::movimientosAnno($cuenta, $anno);
        $this->saldos = Gasto::saldos($cuenta, $anno);
    }

    public function getMovimientos()
    {
        return $this->movimientos;
    }

    public function saldoFinal()
    {
        return optional($this->saldos)->last();
    }

    protected function getSumMovimientos(Gasto $saldo)
    {
        return $this->movimientos
            ->filter(function ($movimiento) use ($saldo) {
                return $movimiento->fecha <= $saldo->fecha;
            })
            ->map->valor_monto
            ->sum();
    }

    public function util(Gasto $saldo)
    {
        return $saldo->monto - $this->getSumMovimientos($saldo);
    }

    public function rentabilidad(Gasto $saldo)
    {
        $sumMovimientos = $this->getSumMovimientos($saldo);

        return $sumMovimientos == 0 ? 0 : $saldo->monto / $sumMovimientos - 1;
    }

    public function rentabilidadAnual($saldoFinal = null)
    {
        if (empty($this->movimientos)) {
            return;
        }

        $fechaIni = optional($this->movimientos->first())->fecha;

        if (is_null($fechaIni)) {
            return;
        }

        $fechaFin = is_null($saldoFinal) ? optional($this->saldoFinal())->fecha : $saldoFinal->fecha;
        $diasInversion = $fechaIni->diffInDays($fechaFin);

        if ($diasInversion != 0) {
            return pow(pow(1 + $this->rentabilidad($saldoFinal), 1 / $diasInversion), 365) - 1;
        }
    }

    public function getJSONRentabilidadesAnual()
    {
        return $this->saldos->count() == 0
            ? ""
            : "['fecha', 'tasa'],"
                .$this->saldos->map(function($saldo) {
                    return "['".$saldo->fecha->format('Y-m-d')."',".$this->rentabilidadAnual($saldo)."]";
                })->implode(',');
    }
}
