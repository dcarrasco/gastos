<?php

namespace App\OrmModel\Acl;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Boolean;
use App\OrmModel\OrmField\HasMany;

class Usuario extends Resource
{
    public $model = 'App\Acl\Usuario';
    public $icono = 'user';
    public $title = 'nombre';
    public $search = [
        'id', 'nombre', 'username', 'email'
    ];
    public $order = 'nombre';

    public function fields()
    {
        return [
            Id::make()->sortable(),

            Text::make('nombre')
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
                ->hideFromIndex(),

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

            HasMany::make('rol', 'rol', 'App\OrmModel\Acl\Rol'),
        ];
    }
}
