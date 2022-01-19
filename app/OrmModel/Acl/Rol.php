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
    public string $model = \App\Models\Acl\Rol::class;
    public string $labelPlural = 'Roles';
    public string $icono = 'server';
    public string $title = 'rol';
    public array $search = [
        'id', 'rol', 'descripcion'
    ];
    public $orderBy = [
        'app_id' => 'asc',
        'rol' => 'asc'
    ];

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('aplicacion', 'app', App::class)
                ->rules('required')
                ->onChange('modulo'),

            Text::make('rol')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:100', 'required'),

            HasMany::make('modulo', 'modulo', Modulo::class)
                ->relationConditions(['app_id' => '@field_value:app_id:NULL'])
                ->relationField('abilities', '{"booleanOptions":["view", "view-any", "create", "update", "delete"]}'),
        ];
    }
}
