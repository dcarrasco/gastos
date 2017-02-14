<?php

namespace App\Inventario;

use App\OrmModel;

class Auditor extends OrmModel
{
    public $modelLabel = 'Auditor';
    public $modelOrder = 'nombre';

    protected $fillable = ['nombre', 'activo'];

    protected $guarded = [];

    public $modelFields = [
        'id' => [
            'tipo'   => OrmModel::TIPO_ID,
        ],
        'nombre' => [
            'label'          => 'Nombre del auditor',
            'tipo'           => OrmModel::TIPO_CHAR,
            'largo'          => 50,
            'texto_ayuda'    => 'M&aacute;ximo 50 caracteres.',
            'es_obligatorio' => true,
            'es_unico'       => true
        ],
        'activo' => [
            'label'          => 'Activo',
            'tipo'           =>  OrmModel::TIPO_BOOLEAN,
            'texto_ayuda'    => 'Indica se el auditor est&aacute; activo dentro del sistema.',
            'es_obligatorio' => true,
            'default'        => 1
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_auditores');
    }

    public function __toString()
    {
        return $this->nombre;
    }
}
