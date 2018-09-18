<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class Centro extends OrmModel
{
    public $model = 'App\Inventario\Centro';
    public $title = 'centro';
    public $search = [
        'centro'
    ];
    public $modelOrder = 'centro';

    public function fields() {
        return [
            Text::make('centro')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),
        ];
    }
}
