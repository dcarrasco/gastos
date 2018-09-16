<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\HasMany;
use App\OrmModel\OrmField\BelongsTo;

class Rol extends OrmModel
{
    // Eloquent
    protected $fillable = ['app_id', 'rol', 'descripcion'];

    // OrmModel
    public $title = 'rol';
    public $search = [
        'id', 'rol', 'descripcion'
    ];
    public $modelOrder = [
        'app_id' => 'asc', 'rol' => 'asc'
    ];


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_rol');
    }

    public function fields()
    {
        return [
            Id::make()->sortable(),

            BelongsTo::make('aplicacion', 'app')
                ->rules('required')
                ->onChange('modulo'),

            Text::make('rol')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:100', 'required'),

            HasMany::make('modulo')
                ->helpText('M&oacute;dulos del rol.')
                ->relationConditions(['app_id' => '@field_value:app_id:NULL']),
        ];
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }

    public function modulo()
    {
        return $this->belongsToMany(Modulo::class, config('invfija.bd_rol_modulo'))->withTimestamps();
    }
}
