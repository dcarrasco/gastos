<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Rol extends OrmModel
{
    public $modelLabel = 'Rol';

    protected $fillable = ['id_app', 'rol', 'descripcion'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo' => OrmField::TIPO_ID,
        ],
        'id_app' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => App::class,
            'textoAyuda' => 'Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.',
            'onchange' => 'modulo',
        ],
        'rol' => [
            'label' => 'Rol',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre del rol. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'descripcion' => [
            'label' => 'Descripci&oacute;n del rol',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 100,
            'textoAyuda' => 'Descripci&oacute;n del rol. M&aacute;ximo 100 caracteres.',
            'esObligatorio' => true,
        ],
        'modulo' => [
            'tipo' => OrmField::TIPO_HAS_MANY,
            'relationModel' => Modulo::class,
            'relation_conditions' => ['id_app' => '@field_value:id_app:NULL'],
            'textoAyuda' => 'M&oacute;dulos del rol.',
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
        return (string) $this->rol;
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
