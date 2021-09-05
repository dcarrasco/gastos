<?php

namespace App\OrmModel\src\Filters;

use Illuminate\Http\Request;
use App\OrmModel\src\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class PerPage extends Filter
{
    protected string $parameterPrefix = '';


    public function options(): array
    {
        return [
            '25' => 25,
            '50' => 50,
            '100' => 100,
        ];
    }
}
