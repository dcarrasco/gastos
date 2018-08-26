<?php

namespace App\Inventario;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class Auditor extends OrmModel
{
    public $modelLabel = 'Auditor';

    public $modelOrder = ['nombre' => 'asc'];

    public $timestamps = true;
    protected $fillable = ['nombre', 'activo'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo' => OrmField::TIPO_ID,
        ],
        'nombre' => [
            'label' => 'Nombre del auditor',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'activo' => [
            'label' => 'Activo',
            'tipo' =>  OrmField::TIPO_BOOLEAN,
            'textoAyuda' => 'Indica se el auditor est&aacute; activo dentro del sistema.',
            'esObligatorio' => true,
            'default' => 1
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_auditores');
    }

    public function __toString()
    {
        return (string) $this->nombre;
    }
}
