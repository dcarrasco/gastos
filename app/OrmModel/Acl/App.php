<?php

namespace App\OrmModel\Acl;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;

class App extends Resource
{
    public $model = 'App\Acl\App';
    public $label = 'Aplicacion';
    public $icono = 'folder-o';
    public $title = 'app';
    public $search = [
        'id', 'app', 'descripcion', 'url', 'icono'
    ];
    public $order = 'app';

    public function fields() {
        return [
            Id::make()->sortable(),

            Text::make('aplicacion', 'app')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required', 'unique'),

            Text::make('url')
                ->rules('max:100')
                ->hideFromIndex(),

            Text::make('icono')
                ->rules('max:50'),
        ];
    }
}
