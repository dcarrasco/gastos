<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\Boolean;

class Catalogo extends OrmModel
{
    public $model = 'App\Inventario\Catalogo';
    public $title = 'descripcion';
    public $search = [
        'catalogo', 'descripcion'
    ];
    public $modelOrder = ['catalogo' => 'asc'];

    public function fields()
    {
        return [
            Text::make('catalogo')
                ->sortable()
                ->rules('max:20', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('pmp')
                ->sortable()
                ->rules('required'),

            Boolean::make('es seriado')
                ->rules('required'),
        ];
    }
}
