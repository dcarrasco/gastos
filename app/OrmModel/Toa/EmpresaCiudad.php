<?php

namespace App\OrmModel\Toa;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\BelongsTo;

class EmpresaCiudad extends Resource
{
    public $model = 'App\Toa\EmpresaCiudad';
    public $icono = 'map-marker';
    public $label = 'Empresa Ciudad TOA';
    public $labelPlural = 'Empresas Ciudades TOA';
    public $title = 'empresa';
    public $search = [
        'id_empresa', 'empresa',
    ];

    public function fields(Request $request)
    {
        return [
            BelongsTo::make('empresa', 'empresaToa', 'App\OrmModel\Toa\Empresa'),

            BelongsTo::make('ciudad', 'ciudadToa', 'App\OrmModel\Toa\Ciudad'),
        ];
    }

}
