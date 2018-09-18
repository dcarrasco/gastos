<?php

namespace App\OrmModel\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class TipoTrabajo extends OrmModel
{
    public $model = 'App\Toa\TipoTrabajo';
    public $icono = 'television';
    public $label = 'Tipo de Trabajo TOA';
    public $title = 'desc_tipo';
    public $search = [
        'id_tipo', 'desc_tipo',
    ];
    public $modelOrder = 'id_tipo';

    public function fields()
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
