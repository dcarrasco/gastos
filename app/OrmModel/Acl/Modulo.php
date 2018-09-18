<?php

namespace App\OrmModel\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\BelongsTo;

class Modulo extends OrmModel
{
    public $model = 'App\Acl\Modulo';
    public $title = 'modulo';
    public $icono = 'list-alt';
    public $search = [
        'id', 'modulo', 'descripcion', 'url', 'icono'
    ];
    public $modelOrder = [
        'app_id' =>'asc',
        'modulo' =>'asc'
    ];

    public function fields()
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('aplicacion', 'app', 'App\OrmModel\Acl\App')
                ->rules('required'),

            Text::make('modulo')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('descripcion')
                ->hideFromIndex()
                ->rules('max:100', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required'),

            Text::make('url')
                ->sortable()
                ->rules('max:50'),

            Text::make('icono')
                ->hideFromIndex()
                ->rules('max:50'),

            Text::make('llave modulo')
                ->hideFromIndex()
                ->rules('max:20', 'required', 'unique'),
        ];
    }
}
