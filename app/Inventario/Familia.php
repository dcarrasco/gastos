<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Familia extends OrmModel
{
    public $modelLabel = 'Familia';

    protected $fillable = ['codigo', 'tipo', 'nombre'];

    protected $guarded = [];

    protected $primaryKey = 'codigo';

    public $incrementing = false;

    public $modelFields = [
        'codigo' => [
            'label' => 'C&oacute;digo de la familia',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'M&aacute;ximo 50 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true,
        ],
        'tipo' => [
            'label' => 'Tipo de familia',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 30,
            'textoAyuda' => 'Seleccione el tipo de familia.',
            'choices' => [
                'FAM' => 'Familia',
                'SUBFAM' => 'SubFamilia'
            ],
            'esObligatorio' => true,
        ],
        'nombre' => [
            'label' => 'Nombre de la familia',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true,
        ],
    ];

    public $modelOrder = ['codigo' => 'asc'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_familias');
    }

    public function __toString()
    {
        return (string) $this->nombre;
    }
}
