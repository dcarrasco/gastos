<?php

namespace App\OrmModel\src\Filters;

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
