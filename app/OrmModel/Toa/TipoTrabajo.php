<?php

namespace App\OrmModel\Toa;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;

class TipoTrabajo extends Resource
{
    public $model = 'App\Toa\TipoTrabajo';
    public $icono = 'television';
    public $label = 'Tipo de Trabajo TOA';
    public $title = 'desc_tipo';
    public $search = [
        'id_tipo', 'desc_tipo',
    ];
    public $order = 'id_tipo';

    public function fields(Request $request)
    {
        return [
            Text::make('id', 'id_tipo')
                ->sortable()
                ->rules('max:30', 'required', 'unique'),

            Text::make('descripcion', 'desc_tipo')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
