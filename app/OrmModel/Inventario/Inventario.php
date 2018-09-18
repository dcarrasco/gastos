<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\OrmField\BelongsTo;

class Inventario extends OrmModel
{
    public $model = 'App\Inventario\Inventario';
    public $title = 'nombre';
    public $search = [
        'id', 'nombre',
    ];
    public $modelOrder = 'nombre';


    public function fields() {
        return [
            Id::make()->sortable(),

            Text::make('nombre')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Boolean::make('activo')
                ->sortable()
                ->rules('required'),

            BelongsTo::make('tipo inventario', 'tipoInventario', 'App\OrmModel\Inventario\TipoInventario')
                ->rules('required'),
        ];
    }
}
