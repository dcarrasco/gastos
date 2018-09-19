<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\OrmField\BelongsTo;

class Inventario extends Resource
{
    public $model = 'App\Inventario\Inventario';
    public $icono = 'list';
    public $title = 'nombre';
    public $search = [
        'id', 'nombre',
    ];
    public $order = 'nombre';


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
