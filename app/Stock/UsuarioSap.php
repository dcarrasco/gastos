<?php

namespace App\Stock;

use App\OrmModel;

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
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 10,
            'texto_ayuda' => 'C&oacute;digo del usuario SAP. M&aacute;ximo 10 caracteres',
            'es_id' => true,
            'es_obligatorio' => true,
            'es_unico' => true
        ],
        'nom_usuario' => [
            'label' => 'Nombre de usuario',
            'tipo' => OrmModel::TIPO_CHAR,
            'largo' => 50,
            'texto_ayuda' => 'Nombre del usuario. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico' => false,
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
