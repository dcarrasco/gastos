<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Modulo extends OrmModel
{
    public $modelLabel = 'Modulo';

    protected $fillable = ['id_app', 'modulo', 'descripcion', 'llave_modulo', 'icono', 'url', 'orden'];

    protected $guarded = [];

    public $modelFields = [
        'id' => OrmField::TIPO_ID,
        'app_id' => [
            'tipo' => OrmField::TIPO_HAS_ONE,
            'relationModel' => App::class,
            'textoAyuda' => 'Aplicaci&oacute;n a la que pertenece el m&oacute;dulo.',
        ],
        'modulo' => [
            'label' => 'Modulo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre del m&oacute;dulo. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'descripcion' => [
            'label' => 'Descripci&oacute;n del m&oacute;dulo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 100,
            'textoAyuda' => 'Descripci&oacute;n del m&oacute;dulo. M&aacute;ximo 100 caracteres.',
            'esObligatorio' => true,
        ],
        'orden' => [
            'label' => 'Orden del m&oacute;dulo',
            'tipo' => OrmField::TIPO_INT,
            'textoAyuda' => 'Orden del m&oacute;dulo en el men&uacute;.',
            'esObligatorio' => true,
        ],
        'url' => [
            'label' => 'Direccion del m&oacute;dulo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Dirección web (URL) del m&oacute;dulo. M&aacute;ximo 50 caracteres.',
        ],
        'icono' => [
            'label' => '&Iacute;cono del m&oacute;dulo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre de archivo del icono del m&oacute;dulo. M&aacute;ximo 50 caracteres.',
        ],
        'llave_modulo' => [
            'label' => 'Llave del m&oacute;dulo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 20,
            'textoAyuda' => 'Cadena de caracteres de seguridad del m&oacute;dulo. M&aacute;ximo 20 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true,
            'mostrarLista' => false,
        ],
    ];

    public $modelOrder = ['app_id' =>'asc', 'modulo' =>'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_modulos');
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
