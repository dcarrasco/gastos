<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\IdField;
use App\OrmModel\OrmField\CharField;
use App\OrmModel\OrmField\HasOneField;
use App\OrmModel\OrmField\NumberField;

class Modulo extends OrmModel
{
    public $modelLabel = 'Modulo';

    protected $fillable = ['id_app', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];

    protected $guarded = [];

    public $modelOrder = ['app_id' =>'asc', 'modulo' =>'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_modulos');
    }

    public function fields()
    {
        return [
            IdField::make()->sortable(),

            HasOneField::make(App::class)
                ->helpText('Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.'),

            CharField::make('modulo')
                ->sortable()
                ->rules('max:50', 'required', 'unique')
                ->helpText('Nombre del m&oacute;dulo. M&aacute;ximo 50 caracteres.'),

            CharField::make('descripcion')
                ->hideFromIndex()
                ->rules('max:100', 'required')
                ->helpText('Descripci&oacute;n del m&oacute;dulo. M&aacute;ximo 100 caracteres.'),

            NumberField::make('orden')
                ->sortable()
                ->rules('required')
                ->helpText('Orden del m&oacute;dulo en el men&uacute;.'),

            CharField::make('url')
                ->sortable()
                ->rules('max:50')
                ->helpText('Dirección web (URL) del m&oacute;dulo. M&aacute;ximo 50 caracteres.'),

            CharField::make('icono')
                ->hideFromIndex()
                ->rules('max:50')
                ->helpText('Nombre de archivo del icono del m&oacute;dulo. M&aacute;ximo 50 caracteres.'),

            CharField::make('llave modulo')
                ->hideFromIndex()
                ->rules('max:20', 'required', 'unique')
                ->helpText('Cadena de caracteres de seguridad del m&oacute;dulo. M&aacute;ximo 20 caracteres.'),
        ];


    }

    public function __toString()
    {
        return (string) $this->modulo;
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
