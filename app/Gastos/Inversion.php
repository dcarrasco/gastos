<?php

namespace App\Gastos;

use App\Gastos\Gasto;
use Illuminate\Http\Request;

class Inversion
{
    protected $movimientos;
    protected $saldos;
    protected $sumMovimientos = 0;

    public function __construct(Request $request)
    {
        $this->movimientos = (new Gasto)->movimientosAnno($request);
        $this->saldos = (new Gasto)->saldos($request);
    }

    public function getMovimientos()
    {
        return $this->movimientos;
    }

    public function saldoFinal()
    {
        return optional($this->saldos)->last();
    }

    protected function getSumMovimientos()
    {
        if ($this->sumMovimientos === 0 and ! empty($this->movimientos)) {
            $this->sumMovimientos = $this->movimientos
                ->map(function($movimiento) {
                    return $movimiento->monto * optional($movimiento->tipoMovimiento)->signo;
                })
                ->sum();
        }

        return $this->sumMovimientos;
    }

    public function util()
    {
        return optional($this->saldoFinal())->monto - $this->getSumMovimientos();
    }

    public function rentabilidad()
    {
        if ($this->getSumMovimientos() == 0) {
            return 0;
        }

        return $this->util()/$this->getSumMovimientos();
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

        $fechaFin = is_null($saldoFinal) ? $this->saldoFinal()->fecha : $saldoFinal->fecha;
        $diasInversion = $fechaIni->diffInDays($fechaFin);

        if ($diasInversion != 0) {
            return pow(pow(1 + $this->rentabilidad(), 1/$diasInversion), 365) - 1;
        }
    }

    public function getAllRentabilidadesAnual()
    {
        return "['fecha', 'tasa'],"
            .$this->saldos->map(function($saldo) {
                return "['".$saldo->fecha->format('Y-m-d')."',".$this->rentabilidadAnual($saldo)."]";
            })->implode(',');
    }
}
