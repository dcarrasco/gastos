<?php

namespace App\OrmModel\Stock;

use App\OrmModel\Resource;
use App\OrmModel\OrmField\Text;

class UsuarioSap extends Resource
{
    public $model = 'App\Stock\UsuarioSap';
    public $label = 'Usuario SAP';
    public $icono = 'user';
    public $title = 'nom_usuario';
    public $search = [
        'usuario', 'nom_usuario'
    ];
    public $order = 'usuario';

    public function fields() {
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
