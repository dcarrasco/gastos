<?php

namespace App\OrmModel\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class ClaseMovimiento extends OrmModel
{
    public $model = 'App\Stock\ClaseMovimiento';
    public $icono = 'th';
    public $title = 'nom_usuario';
    public $search = [
        'cmv', 'des_cmv'
    ];
    public $modelOrder = 'cmv';

    public function fields() {
        return [
            Text::make('cmv')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('descripcion','des_cmv')
                ->sortable()
                ->rules('max:50', 'required'),
        ];
    }

}
