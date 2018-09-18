<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\BelongsTo;

class TipoUbicacion extends OrmModel
{
    public $model = 'App\Inventario\TipoUbicacion';
    public $label = 'Tipo de Ubicacion';
    public $title = 'tipo_ubicacion';
    public $search = [
        'id', 'tipo_ubicacion'
    ];
    public $modelOrder = 'tipo_inventario';

    public function fields() {
        return [
            Id::make()->sortable(),

            BelongsTo::make('tipo inventario', 'tipoInventario', 'App\OrmModel\Inventario\TipoInventario')
                ->rules('required'),

            Text::make('tipo ubicacion')
                ->sortable()
                ->rules('max:30', 'required', 'unique'),
        ];
    }
}
