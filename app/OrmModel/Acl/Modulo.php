<?php

namespace App\OrmModel\Acl;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\Number;
use App\OrmModel\src\OrmField\BelongsTo;

class Modulo extends Resource
{
    public $model = 'App\Models\Acl\Modulo';
    public $title = 'modulo';
    public $icono = 'list-alt';
    public $search = [
        'id', 'modulo', 'descripcion', 'url', 'icono'
    ];
    public $orderBy = [
        'app_id' => 'asc',
        'modulo' => 'asc'
    ];

    public function fields(Request $request): array
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
                ->sortable()
                ->rules('max:50'),

            Text::make('llave modulo')
                ->hideFromIndex()
                ->rules('max:20', 'required', 'unique'),
        ];
    }
}
