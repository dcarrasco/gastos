<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Boolean;

class Auditor extends OrmModel
{
    public $model = 'App\Inventario\Auditor';
    public $title = 'nombre';
    public $search = [
        'id', 'nombre'
    ];
    public $modelOrder = ['nombre' => 'asc'];

    public function fields()
    {
        return [
            Id::make()->sortable(),

            Text::make('nombre')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Boolean::make('activo')
                ->sortable()
                ->rules('required'),
        ];
    }
}
