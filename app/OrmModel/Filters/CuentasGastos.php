<?php

namespace App\OrmModel\Filters;

use Illuminate\Http\Request;
use App\OrmModel\src\Filters\Filter;

class CuentasGastos extends Filter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('cuenta_id', $value);
    }

    public function options()
    {
        return [
            '45006288' => 1,
            'VISA' => 2,
        ];
    }
}
