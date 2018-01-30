<?php

namespace App\Acl;

use App\OrmModel;

class Modulo extends OrmModel
{
    public $modelLabel = 'Modulo';

    protected $fillable = ['id_app', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo' => OrmModel::TIPO_ID,
        ],
        'id_app' => [
            'tipo' => OrmModel::TIPO_HAS_ONE,
            'relation_model' => App::class,
            'texto_ayuda' => 'Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.',
        ],
        'modulo' => [
            'label' => 'Modulo',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Nombre del m&oacute;dulo. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico' => true
        ],
        'descripcion' => [
            'label' => 'Descripci&oacute;n del m&oacute;dulo',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 100,
            'texto_ayuda' => 'Descripci&oacute;n del m&oacute;dulo. M&aacute;ximo 100 caracteres.',
            'es_obligatorio' => true,
        ],
        'orden' => [
            'label' => 'Orden del m&oacute;dulo',
            'tipo' => OrmModel::TIPO_INT,
            'texto_ayuda' => 'Orden del m&oacute;dulo en el men&uacute;.',
            'es_obligatorio' => true,
        ],
        'url' => [
            'label' => 'Direccion del m&oacute;dulo',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Dirección web (URL) del m&oacute;dulo. M&aacute;ximo 50 caracteres.',
        ],
        'icono' => [
            'label' => '&Iacute;cono del m&oacute;dulo',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Nombre de archivo del icono del m&oacute;dulo. M&aacute;ximo 50 caracteres.',
        ],
        'llave_modulo' => [
            'label' => 'Llave del m&oacute;dulo',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 20,
            'texto_ayuda' => 'Cadena de caracteres de seguridad del m&oacute;dulo. M&aacute;ximo 20 caracteres.',
            'es_obligatorio' => true,
            'es_unico' => true,
            'mostrar_lista' => false,
        ],
    ];

    public $modelOrder = ['id_app' =>'asc', 'modulo' =>'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_modulos');
    }

    public function __toString()
    {
        return $this->modulo;
    }

    public function app()
    {
        return $this->belongsTo(App::class, 'id_app');
    }
}
