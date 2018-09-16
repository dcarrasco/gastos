<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Id;
use App\OrmModel\OrmField\Text;
use App\OrmModel\OrmField\HasMany;
use App\OrmModel\OrmField\BelongsTo;

class Rol extends OrmModel
{
    public $modelLabel = 'Rol';
    public $title = 'rol';

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
            Id::make()->sortable(),

            BelongsTo::make('aplicacion', 'app')
                ->rules('required')
                ->helpText('Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.')
                ->onChange('modulo'),

            Text::make('rol')
                ->sortable()
                ->rules('max:50', 'required', 'unique')
                ->helpText('Nombre del rol. M&aacute;ximo 50 caracteres.'),

            Text::make('descripcion')
                ->sortable()
                ->rules('max:100', 'required')
                ->helpText('Descripci&oacute;n del rol. M&aacute;ximo 100 caracteres.'),

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
