<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class UsuarioSap extends OrmModel
{
    public $modelLabel = 'Usuarios SAP';

    protected $fillable = ['usuario', 'nom_usuario'];

    protected $guarded = [];

    protected $primaryKey = 'usuario';

    public $incrementing = false;

    public $modelFields = [
        'usuario' => [
            'label' => 'Codigo Usuario',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 10,
            'textoAyuda' => 'C&oacute;digo del usuario SAP. M&aacute;ximo 10 caracteres',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'nom_usuario' => [
            'label' => 'Nombre de usuario',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Nombre del usuario. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => false,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_usuarios_sap');
    }

    public function __toString()
    {
        return (string) $this->nom_usuario;
    }
}
