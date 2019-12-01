<?php

namespace App\OrmModel\Filters;

use Illuminate\Http\Request;
use App\OrmModel\src\Filters\Filter;

class FamiliaSubFamilia extends Filter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('tipo', $value);
    }

    public function options()
    {
        return [
            'Familia' => 'FAM',
            'SubFamilia' => 'SUBFAM',
        ];
    }
}
