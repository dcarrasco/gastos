<?php

namespace App\OrmModel\Inventario;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;

class Centro extends Resource
{
    public $model = 'App\Inventario\Centro';
    public $icono = 'th';
    public $title = 'centro';
    public $search = [
        'centro'
    ];
    public $order = 'centro';

    public function fields(Request $request)
    {
        return [
            Text::make('centro')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),
        ];
    }
}
