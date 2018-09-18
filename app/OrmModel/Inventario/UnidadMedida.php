<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class UnidadMedida extends OrmModel
{
    public $model = 'App\Inventario\UnidadMedida';
    public $icono = 'balance-scale';
    public $title = 'desc_unidad';
    public $search = [
        'centro'
    ];
    public $modelOrder = 'desc_unidad';

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
