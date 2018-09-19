<?php

namespace App\OrmModel\Toa;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class Tecnico extends Resource
{
    public $model = 'App\Toa\Tecnico';
    public $icono = 'user';
    public $title = 'tecnico';
    public $search = [
        'id_tecnico', 'tecnico', 'rut',
    ];
    public $order = 'id_tecnico';

    public function fields() {
        return [
            Text::make('id tecnico')
                ->sortable()
                ->rules('max:20', 'required', 'unique'),

            Text::make('tecnico')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('tecnico')
                ->sortable()
                ->rules('max:50', 'required'),

            Text::make('rut')
                ->sortable()
                ->rules('max:20', 'required'),

            BelongsTo::make('empresa', 'empresaToa', 'App\OrmModel\Toa\Empresa'),

            // BelongsTo::make('ciudad', 'ciudadToa', 'App\OrmModel\Toa\Ciudad'),
        ];
    }
}
