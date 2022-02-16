<?php

namespace App\Models\Gastos;

use App\Models\Gastos\Gasto;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class Inversion
{
    /** @var Collection<int, Gasto> */
    protected $movimientos;

    /** @var Collection<int, Gasto> */
    protected $saldos;


    public function __construct(int $cuenta, int $anno)
    {
        $this->movimientos = Gasto::movimientosAnno($cuenta, $anno);
        $this->saldos = Gasto::saldos($cuenta, $anno);
    }

    /** @return Collection<int, Gasto> */
    public function saldos(): Collection
    {
        return $this->saldos;
    }

    /** @return Collection<int, Gasto> */
    public function getMovimientos(): Collection
    {
        return $this->movimientos;
    }

    public function saldoFinal(): Gasto
    {
        return $this->saldos->isEmpty() ? new Gasto() : $this->saldos->last();
    }

    protected function getSumMovimientos(Gasto $saldo): int
    {
        if (is_null($saldo->fecha)) {
            return 0;
        }

        return $this->movimientos
            ->filter->isBeforeDate($saldo->fecha)
            ->map->valor_monto
            ->sum();
    }

    public function util(Gasto $saldo): int
    {
        return $saldo->monto - $this->getSumMovimientos($saldo);
    }

    public function utilHasta(Carbon $fecha): int
    {
        return $this->util($this->ultimoSaldo($fecha));
    }

    public function ultimoSaldo(Carbon $fecha): Gasto
    {
        return $this->saldos()
            ->filter->isBeforeDate($fecha)
            ->last() ?? new Gasto();
    }

    /** @return Collection<string, int> */
    public function evolUtil(): Collection
    {
        return $this->saldos
            ->mapWithKeys(fn($saldo) => [(string) $saldo->fecha->format('Y-m-d') => $this->util($saldo)]);
    }

    public function rentabilidad(Gasto $saldo): float
    {
        $sumMovimientos = $this->getSumMovimientos($saldo);

        return $sumMovimientos == 0 ? 0 : 100 * ($saldo->monto / $sumMovimientos - 1);
    }

    public function fmtRentabilidad(Gasto $saldo): string
    {
        return fmtCantidad($this->rentabilidad($saldo), 2) . '%';
    }

    public function rentabilidadAnual(Gasto $saldoFinal): float
    {
        if ($this->movimientos->isEmpty()
            or is_null($fechaIni = optional($this->movimientos->first())->fecha)
            or ($diasInversion = $fechaIni->diffInDays($saldoFinal->fecha)) == 0
        ) {
            return 0;
        }

        return 100 * (pow(pow(1 + $this->rentabilidad($saldoFinal) / 100, 1 / $diasInversion), 365) - 1);
    }

    public function fmtRentabilidadAnual(Gasto $saldo): string
    {
        return fmtCantidad($this->rentabilidadAnual($saldo), 2) . '%';
    }

    public function getJSONRentabilidadesAnual(): string
    {
        if ($this->saldos->isEmpty()) {
            return '';
        }

        $jsonRentabilidades = json_encode([
            'label' => $this->saldos->pluck('fecha')
                ->map->format('Y-m-d')
                ->toArray(),
            'rentabilidad' => $this->saldos->map(fn($saldo) => $this->rentabilidadAnual($saldo))
                ->toArray(),
            'saldo' => $this->saldos->pluck('monto')
                ->toArray(),
        ]);

        if ($jsonRentabilidades == false) {
            return '';
        }

        return $jsonRentabilidades;
    }
}
