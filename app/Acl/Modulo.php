<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\Number;
use App\OrmModel\OrmField\BelongsTo;

class Modulo extends OrmModel
{
    // Eloquent
    protected $fillable = ['id_app', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];
    protected $guarded = [];

    // OrmModel
    public $title = 'modulo';
    public $search = [
        'id', 'modulo', 'descripcion', 'url', 'icono'
    ];
    public $modelOrder = [
        'app_id' =>'asc',
        'modulo' =>'asc'
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_modulos');
    }

    public function fields()
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('aplicacion', 'app')
                ->rules('required'),

            Text::make('modulo')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('descripcion')
                ->hideFromIndex()
                ->rules('max:100', 'required'),

            Number::make('orden')
                ->sortable()
                ->rules('required'),

            Text::make('url')
                ->sortable()
                ->rules('max:50'),

            Text::make('icono')
                ->hideFromIndex()
                ->rules('max:50'),

            Text::make('llave modulo')
                ->hideFromIndex()
                ->rules('max:20', 'required', 'unique'),
        ];
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
