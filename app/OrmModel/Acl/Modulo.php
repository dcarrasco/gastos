<?php

namespace App\OrmModel\Acl;

use App\OrmModel\src\OrmField\BelongsTo;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Number;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\Resource;
use Illuminate\Http\Request;

class Modulo extends Resource
{
    public string $model = \App\Models\Acl\Modulo::class;

    public string $title = 'modulo';

    public string $icono = 'list-alt';

    public array $search = [
        'id', 'modulo', 'descripcion', 'url', 'icono',
    ];

    public $orderBy = [
        'app_id' => 'asc',
        'modulo' => 'asc',
    ];

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('aplicacion', 'app', App::class)
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
                ->rules('required', 'max:50'),

            Text::make('icono')
                ->sortable()
                ->rules('required', 'max:50'),

            Text::make('llave modulo')
                ->hideFromIndex()
                ->rules('max:20', 'required', 'unique'),
        ];
    }
}
