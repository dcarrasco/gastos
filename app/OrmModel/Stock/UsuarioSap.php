<?php

namespace App\OrmModel\Stock;

use App\OrmModel\Resource;
use Illuminate\Http\Request;
use App\OrmModel\OrmField\Text;

class UsuarioSap extends Resource
{
    public $model = 'App\Stock\UsuarioSap';
    public $label = 'Usuario SAP';
    public $labelPlural = 'Usuarios SAP';
    public $icono = 'user';
    public $title = 'nom_usuario';
    public $search = [
        'usuario', 'nom_usuario'
    ];
    public $orderBy = 'usuario';

    public function fields(Request $request)
    {
        return [
            Text::make('usuario')
                ->sortable()
                ->rules('max:10', 'required', 'unique'),

            Text::make('nombre','nom_usuario')
                ->sortable()
                ->rules('max:50', 'required'),
        ];
    }
}
