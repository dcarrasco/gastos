<?php

namespace App\OrmModel\Acl;

use Illuminate\Http\Request;
use App\OrmModel\src\Resource;
use App\OrmModel\src\OrmField\Id;
use App\OrmModel\src\OrmField\Text;
use App\OrmModel\src\OrmField\Boolean;
use App\OrmModel\src\OrmField\HasMany;
use App\OrmModel\src\OrmField\Gravatar;
use App\OrmModel\Filters\UsuariosActivos;

class Usuario extends Resource
{
    public string $model = \App\Models\Acl\Usuario::class;
    public string $icono = 'user';
    public string $title = 'nombre';
    public array $search = [
        'id', 'nombre', 'username', 'email'
    ];
    public $orderBy = 'nombre';

    public function fields(Request $request): array
    {
        return [
            Id::make()->sortable(),

            Gravatar::make(),

            Text::make('Nombre')
                ->sortable()
                ->rules('max:45', 'required', 'unique'),

            Boolean::make('activo')
                ->sortable()
                ->rules('required'),

            Text::make('username')
                ->sortable()
                ->rules('max:30', 'required', 'unique'),

            Text::make('password')
                ->rules('max:100')
                ->hideFromIndex()
                ->hideFromDetail()
                ->hideFromForm(),

            Text::make('email')
                ->sortable()
                ->rules('max:40'),

            Text::make('fecha login')
                ->rules('max:40')
                ->hideFromIndex(),

            Text::make('direccion ip', 'ip_login')
                ->rules('max:30')
                ->hideFromIndex(),

            Text::make('agente', 'agente_login')
                ->rules('max:200')
                ->hideFromIndex(),

            HasMany::make('rol', 'rol', Rol::class),
        ];
    }

    public function filters(Request $request): array
    {
        return [
            new UsuariosActivos(),
        ];
    }
}
