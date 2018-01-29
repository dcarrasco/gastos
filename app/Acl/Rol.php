<?php

namespace App\Acl;

use App\OrmModel;

class Rol extends OrmModel
{
    public $modelLabel = 'Rol';

    protected $fillable = ['id_app', 'rol', 'descripcion'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo'   => OrmModel::TIPO_ID,
        ],
        'id_app' => [
            'tipo'           => OrmModel::TIPO_HAS_ONE,
            'relation_model' => App::class,
            'texto_ayuda'    => 'Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.',
            'onchange'       => 'modulo',
        ],
        'rol' => [
            'label'          => 'Rol',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Nombre del rol. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'descripcion' => [
            'label'          => 'Descripci&oacute;n del rol',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 100,
            'texto_ayuda'    => 'Descripci&oacute;n del rol. M&aacute;ximo 100 caracteres.',
            'es_obligatorio' => true,
        ],
        'modulo' => [
            'tipo'                   => OrmModel::TIPO_HAS_MANY,
            'relation_model'         => Modulo::class,
            'relation_conditions'    => ['id_app' => '@field_value:id_app:NULL'],
            'texto_ayuda'            => 'M&oacute;dulos del rol.',
        ],
    ];

    public $modelOrder = ['id_app' => 'asc', 'rol' => 'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_rol');
    }

    public function __toString()
    {
        return $this->rol;
    }

    public function app()
    {
        return $this->belongsTo(App::class, 'id_app');
    }

    public function modulo()
    {
        return $this->belongsToMany(Modulo::class, config('invfija.bd_rol_modulo'), 'id_rol', 'id_modulo');
    }

}
