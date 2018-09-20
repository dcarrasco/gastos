<?php

namespace App\OrmModel\Stock;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;

class ClaseMovimiento extends Resource
{
    public $model = 'App\Stock\ClaseMovimiento';
    public $label = 'Clase de Movimiento SAP';
    public $icono = 'th';
    public $title = 'nom_usuario';
    public $search = [
        'cmv', 'des_cmv'
    ];
    public $orderBy = 'cmv';

    public function fields(Request $request)
    {
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
