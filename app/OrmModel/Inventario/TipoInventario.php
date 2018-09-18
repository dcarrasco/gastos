<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class TipoInventario extends OrmModel
{
    public $model = 'App\Inventario\TipoInventario';
    public $label = 'Tipo de inventario';
    public $title = 'desc_tipo_inventario';
    public $search = [
        'desc_tipo_inventario'
    ];
    public $modelOrder = ['id_tipo_inventario' => 'asc'];

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
