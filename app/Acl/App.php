<?php

namespace App\Acl;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class App extends OrmModel
{
    public $modelLabel = 'Aplicacion';

    protected $fillable = ['app', 'descripcion', 'orden', 'url', 'icono'];

    protected $guarded = [];

    public $modelFields = [
        'id' => OrmField::TIPO_ID,
        'app' => [
            'label' => 'Aplicaci&oacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'descripcion' => [
            'label' => 'Descripci&oacute;n de la Aplicaci&oacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Breve descripcion de la aplicaci&oacute;n. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
        ],
        'orden' => [
            'label' => 'Orden de la Aplicaci&oacute;n',
            'tipo' => OrmField::TIPO_INT,
            'textoAyuda' => 'Orden de la aplicaci&oacute;n en el menu.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'url' => [
            'label' => 'Direcci&oacute;n de la Aplicaci&oacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 100,
            'mostrarLista' => false,
            'textoAyuda' => 'Direcci&oacute;n web (URL) de la aplicaci&oacute;n. M&aacute;ximo 100 caracteres.',
        ],
        'icono' => [
            'label' => '&Iacute;cono de la aplicaci&oacute;n',
            'mostrarLista' => false,
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre del archivo del &iacute;cono de la aplicaci&oacute;n. '
                .'M&aacute;ximo 50 caracteres.',
        ],
    ];

    public $modelOrder = 'app';

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
