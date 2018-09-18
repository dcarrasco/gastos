<?php

namespace App\OrmModel\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;

class Ciudad extends OrmModel
{
    public $model = 'App\Toa\Ciudad';
    public $title = 'ciudad';
    public $search = [
        'id_ciudad', 'ciudad',
    ];
    public $modelOrder = 'orden';

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
