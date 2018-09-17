<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class UsuarioSap extends OrmModel
{
    // Eloquent
    protected $fillable = ['usuario', 'nom_usuario'];
    protected $primaryKey = 'usuario';
    public $incrementing = false;
    public $timestamps = false;

    // OrmModel
    public $title = 'nom_usuario';
    public $search = [
        'usuario', 'nom_usuario'
    ];
    public $modelOrder = 'usuario';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_usuarios_sap');
    }

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
