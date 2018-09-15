<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\IdField;
use App\OrmModel\OrmField\CharField;
use App\OrmModel\OrmField\HasOneField;
use App\OrmModel\OrmField\HasManyField;

class Rol extends OrmModel
{
    public $modelLabel = 'Rol';

    protected $fillable = ['app_id', 'rol', 'descripcion'];

    protected $guarded = [];

    public $modelOrder = ['app_id' => 'asc', 'rol' => 'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_rol');
    }

    public function fields()
    {
        return [
            IdField::make()->sortable(),

            HasOneField::make('App::class')
                ->helpText('Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.')
                ->onChange('modulo'),

            CharField::make('rol')
                ->sortable()
                ->rules('max:50', 'required', 'unique')
                ->helpText('Nombre del rol. M&aacute;ximo 50 caracteres.'),

            CharField::make('descripcion')
                ->sortable()
                ->rules('max:100', 'required')
                ->helpText('Descripci&oacute;n del rol. M&aacute;ximo 100 caracteres.'),

            HasManyField::make(Modulo::class)
                ->helpText('M&oacute;dulos del rol.'),
                // 'relationConditions' => ['app_id' => '@field_value:app_id:NULL'],
        ];
    }

    public function __toString()
    {
        return (string) $this->rol;
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
