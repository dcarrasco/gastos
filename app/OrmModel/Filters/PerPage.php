<?php

namespace App\OrmModel\Filters;

use Illuminate\Http\Request;
use App\OrmModel\Filters\Filter;

class PerPage extends Filter
{
    protected $parameterPrefix = '';

    public function apply(Request $request, $query, $value)
    {
        return $query;
    }

    public function options()
    {
        return [
            '10' => 10,
            '25' => 25,
            '50' => 50,
            '100' => 100,
        ];
    }
}
