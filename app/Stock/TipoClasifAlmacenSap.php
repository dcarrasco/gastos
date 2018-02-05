<?php

namespace App\Stock;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class TipoClasifAlmacenSap extends OrmModel
{
    public $modelLabel = 'Tipo Clasificaci&oacute;n de Almac&eacute;n SAP';

    protected $fillable = ['tipo', 'color'];

    protected $guarded = [];

    protected $primaryKey = 'id_tipoclasif';
    // public $incrementing = false;

    public $modelFields = [
        'id_tipoclasif' => [
            'tipo' => OrmField::TIPO_INT,
        ],
        'tipo' => [
            'label' => 'Tipo Clasificaci&oacute;n de Almac&eacute;n',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Tipo Clasificaci&oacute;n del almac&eacute;n. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
        ],
        'color' => [
            'label' => 'Color del tipo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Color del tipo para graficar. M&aacute;ximo 20 caracteres.',
            'esObligatorio' => false,
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
