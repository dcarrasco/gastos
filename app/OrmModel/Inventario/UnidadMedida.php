<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Text;

class UnidadMedida extends Resource
{
    public $model = 'App\Inventario\UnidadMedida';
    public $label = 'Unidad de medida';
    public $icono = 'balance-scale';
    public $title = 'desc_unidad';
    public $search = [
        'centro'
    ];
    public $order = 'desc_unidad';

    public function fields() {
        return [
            Text::make('unidad')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('descripcion', 'desc_unidad')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
    }
}
