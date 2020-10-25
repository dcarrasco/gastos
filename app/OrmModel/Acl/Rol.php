<?php

namespace App\OrmModel\Acl;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\HasMany;
use App\OrmModel\src\OrmField\BelongsTo;

class Rol extends Resource
{
    public $model = 'App\Models\Acl\Rol';
    public $labelPlural = 'Roles';
    public $icono = 'server';
    public $title = 'rol';
    public $search = [
        'id', 'rol', 'descripcion'
    ];
    public $orderBy = [
        'app_id' => 'asc', 'rol' => 'asc'
    ];

    public function fields(Request $request): array
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
                ->relationConditions(['app_id' => '@field_value:app_id:NULL'])
                ->relationField('abilities', '{"booleanOptions":["view", "view-any", "create", "update", "delete"]}'),
        ];
    }
}
