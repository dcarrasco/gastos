<?php

namespace App\OrmModel\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\BelongsTo;

class EmpresaCiudad extends OrmModel
{
    public $model = 'App\Toa\EmpresaCiudad';
    public $label = 'Empresa Ciudad TOA';
    public $title = 'empresa';
    public $search = [
        'id_empresa', 'empresa',
    ];

    public function fields()
    {
        return [
            BelongsTo::make('empresa', 'empresaToa', 'App\OrmModel\Toa\Empresa'),

            BelongsTo::make('ciudad', 'ciudadToa', 'App\OrmModel\Toa\Ciudad'),
        ];
    }

}
