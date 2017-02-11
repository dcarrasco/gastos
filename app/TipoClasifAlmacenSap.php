<?php

namespace App;

class TipoClasifAlmacenSap extends OrmModel
{
    public $modelLabel = 'Tipo Clasificaci&oacute;n de Almac&eacute;n SAP';

    protected $fillable = [
        'tipo', 'color'
    ];

    protected $guarded = [];

    protected $primaryKey = 'id_tipoclasif';
    // public $incrementing = false;

    public $modelFields = [
        'id_tipoclasif' => [
            'tipo' => OrmModel::TIPO_INT,
        ],
        'tipo' => [
            'label'          => 'Tipo Clasificaci&oacute;n de Almac&eacute;n',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Tipo Clasificaci&oacute;n del almac&eacute;n. M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
        ],
        'color' => [
            'label'          => 'Color del tipo',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'Color del tipo para graficar. M&aacute;ximo 20 caracteres.',
            'es_obligatorio' => false,
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipo_clasifalm_sap');
    }

    public function __toString()
    {
        return (string) $this->tipo;
    }
}
