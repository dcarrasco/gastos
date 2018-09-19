<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Text;

class TipoInventario extends Resource
{
    public $model = 'App\Inventario\TipoInventario';
    public $label = 'Tipo de inventario';
    public $icono = 'th';
    public $title = 'desc_tipo_inventario';
    public $search = [
        'desc_tipo_inventario'
    ];
    public $order = ['id_tipo_inventario' => 'asc'];

    public function fields() {
        return [
            Text::make('id tipo inventario')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('desc tipo inventario')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
