<?php

namespace App\OrmModel\Filters;

use App\OrmModel\src\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CuentasGastos extends Filter
{
    public function apply(Request $request, Builder $query, $value): Builder
    {
        return $query->where('cuenta_id', $value);
    }

    public function options(): array
    {
        return [
            '45006288' => 1,
            'VISA' => 2,
            'FM Gran Valor S-A' => 3,
            'FM Preferencial Ahorro' => 6,
        ];
    }
}
