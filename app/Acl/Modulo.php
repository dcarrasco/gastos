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
                ->rules('required')
                ->helpText('Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.'),

            Text::make('modulo')
                ->sortable()
                ->rules('max:50', 'required', 'unique')
                ->helpText('Nombre del m&oacute;dulo. M&aacute;ximo 50 caracteres.'),

            Text::make('descripcion')
                ->hideFromIndex()
                ->rules('max:100', 'required')
                ->helpText('Descripci&oacute;n del m&oacute;dulo. M&aacute;ximo 100 caracteres.'),

            Number::make('orden')
                ->sortable()
                ->rules('required')
                ->helpText('Orden del m&oacute;dulo en el men&uacute;.'),

            Text::make('url')
                ->sortable()
                ->rules('max:50')
                ->helpText('Dirección web (URL) del m&oacute;dulo. M&aacute;ximo 50 caracteres.'),

            Text::make('icono')
                ->hideFromIndex()
                ->rules('max:50')
                ->helpText('Nombre de archivo del icono del m&oacute;dulo. M&aacute;ximo 50 caracteres.'),

            Text::make('llave modulo')
                ->hideFromIndex()
                ->rules('max:20', 'required', 'unique')
                ->helpText('Cadena de caracteres de seguridad del m&oacute;dulo. M&aacute;ximo 20 caracteres.'),
        ];
    }

    public function app()
    {
        return $this->belongsTo(App::class);
    }
}
