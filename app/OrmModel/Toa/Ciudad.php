<?php

namespace App\OrmModel\Toa;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;

class Ciudad extends Resource
{
    public $model = 'App\Toa\Ciudad';
    public $icono = 'map-marker';
    public $title = 'ciudad';
    public $search = [
        'id_ciudad', 'ciudad',
    ];
    public $order = 'orden';

    public function fields()
    {
        return [
            Text::make('id', 'id_ciudad')
                ->sortable()
                ->rules('max:5', 'required', 'unique'),

            Text::make('ciudad')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required'),

        ];
    }
}
