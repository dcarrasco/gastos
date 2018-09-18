<?php

namespace App\OrmModel\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class UsuarioSap extends OrmModel
{
    public $model = 'App\Stock\UsuarioSap';
    public $icono = 'user';
    public $title = 'nom_usuario';
    public $search = [
        'usuario', 'nom_usuario'
    ];
    public $modelOrder = 'usuario';

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
