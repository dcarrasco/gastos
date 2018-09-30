<?php

namespace App\OrmModel\Acl;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\HasMany;
use App\OrmModel\OrmField\BelongsTo;

class Rol extends Resource
{
    public $model = 'App\Acl\Rol';
    public $labelPlural = 'Roles';
    public $icono = 'server';
    public $title = 'rol';
    public $search = [
        'id', 'rol', 'descripcion'
    ];
    public $orderBy = [
        'app_id' => 'asc', 'rol' => 'asc'
    ];

    public function fields(Request $request)
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('aplicacion', 'app', 'App\OrmModel\Acl\App')
                ->rules('required')
                ->onChange('modulo'),

            Text::make('rol')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:100', 'required'),

            HasMany::make('modulo', 'modulo', 'App\OrmModel\Acl\Modulo')
                ->relationConditions(['app_id' => '@field_value:app_id:NULL']),
        ];
    }
}
