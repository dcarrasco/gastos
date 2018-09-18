<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class Almacen extends OrmModel
{
    public $model = 'App\Inventario\Almacen';
    public $icono = 'home';
    public $title = 'almacen';
    public $search = [
        'centro'
    ];
    public $modelOrder = 'almacen';

    public function fields() {
        return [
            Text::make('almacen')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),
        ];
    }
}
