<?php

namespace App\OrmModel\Metrics;

use Illuminate\Http\Request;
use App\Models\Gastos\Inversion;
use App\OrmModel\src\Metrics\Value;
use Illuminate\Database\Eloquent\Builder;

class UtilInversiones extends Value
{
    protected $cuentasInversiones = [3, 6];

    public function calculate(Request $request): array
    {
        return [
            'currentValue' => $this->calculateUtil($request, $this->currentRange($request)),
            'previousValue' => $this->calculateUtil($request, $this->previousRange($request)),
        ];
    }

    protected function calculateUtil(Request $request, array $range): int
    {
        [$fechaDesde, $fechaHasta] = $range;

        return collect($this->cuentasInversiones)
            ->map(function ($cuenta) use ($fechaHasta) {
                return (new Inversion($cuenta, $fechaHasta->year))
                    ->utilHasta($fechaHasta);
            })
            ->sum();
    }

    protected function filter(Request $request, Builder $query): Builder
    {
        return $query->whereIn('cuenta_id', $this->cuentasInversiones);
    }

    public function ranges(): array
    {
        return [
            'YTD' => 'Year To Date',
        ];
    }
}
