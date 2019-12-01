<?php

namespace App\OrmModel\Acl;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\Number;

class App extends Resource
{
    public $model = 'App\Acl\App';
    public $label = 'Aplicacion';
    public $labelPlural = 'Aplicaciones';
    public $icono = 'folder-o';
    public $title = 'app';
    public $search = [
        'id', 'app', 'descripcion', 'url', 'icono'
    ];
    public $orderBy = 'app';

    public function fields(Request $request) {
        return [
            Id::make()->sortable(),

            Text::make('Aplicacion', 'app')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('Descripcion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('Orden')
                ->sortable()
                ->rules('required', 'unique'),

            Text::make('URL', 'url')
                ->rules('max:100')
                ->hideFromIndex(),

            Text::make('Icono')
                ->rules('max:50'),
        ];
    }
}
