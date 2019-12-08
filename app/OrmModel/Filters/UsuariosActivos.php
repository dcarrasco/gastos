<?php

namespace App\OrmModel\Filters;

use Illuminate\Http\Request;
use App\OrmModel\src\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class UsuariosActivos extends Filter
{
    public function apply(Request $request, Builder $query, $value): Builder
    {
        return $query->where('activo', $value);
    }

    public function options(): array
    {
        return [
            'Activos' => 1,
            'Inactivos' => 0,
        ];
    }
}
