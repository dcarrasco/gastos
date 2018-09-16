<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;

class App extends OrmModel
{
    // Eloquent
    protected $fillable = ['app', 'descripcion', 'orden', 'url', 'icono'];
    protected $guarded = [];

    // OrmModel
    public $label = 'Aplicacion';
    public $title = 'app';
    public $search = [
        'id', 'app', 'descripcion', 'url', 'icono'
    ];
    public $modelOrder = 'app';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_app');
    }

    public function fields() {
        return [
            Id::make()->sortable(),

            Text::make('aplicacion', 'app')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:50', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required', 'unique'),

            Text::make('url')
                ->rules('max:100')
                ->hideFromIndex(),

            Text::make('icono')
                ->rules('max:50'),
        ];
    }
}
