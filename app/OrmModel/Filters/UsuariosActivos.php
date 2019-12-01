<?php

namespace App\OrmModel\Filters;

use Illuminate\Http\Request;
use App\OrmModel\src\Filters\Filter;

class UsuariosActivos extends Filter
{
    public function apply(Request $request, $query, $value)
    {
        return $query->where('activo', $value);
    }

    public function options()
    {
        return [
            'Activos' => 1,
            'Inactivos' => 0,
        ];
    }
}
