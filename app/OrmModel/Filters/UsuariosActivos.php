<?php

namespace App\OrmModel\Filters;

use App\OrmModel\src\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

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
