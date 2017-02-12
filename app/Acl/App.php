<?php

namespace App\Acl;

use App\OrmModel;

class App extends OrmModel
{
    public $modelLabel = 'Aplicacion';

    protected $fillable = [
        'app', 'descripcion', 'orden', 'url', 'icono',
    ];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo'   => OrmModel::TIPO_ID,
        ],
        'app' => [
            'label'          => 'Aplicaci&oacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Nombre de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'descripcion' => [
            'label'          => 'Descripci&oacute;n de la Aplicaci&oacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Breve descripcion de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
        ],
        'orden' => [
            'label'          => 'Orden de la Aplicaci&oacute;n',
            'tipo'           => OrmModel::TIPO_INT,
            'texto_ayuda'    => 'Orden de la aplicaci&oacute;n en el menu.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'url' => [
            'label'          => 'Direcci&oacute;n de la Aplicaci&oacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 100,
            'texto_ayuda'    => 'Direcci&oacute;n web (URL) de la aplicaci&oacute;n. M&aacute;ximo 100 caracteres.',
        ],
        'icono' => [
            'label'          => '&Iacute;cono de la aplicaci&oacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Nombre del archivo del &iacute;cono de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.',
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_app');
    }

    public function __toString()
    {
        return (string) $this->app;
    }

}
