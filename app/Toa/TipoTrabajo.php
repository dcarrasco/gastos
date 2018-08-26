<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField;

class TipoTrabajo extends OrmModel
{
    public $modelLabel = 'Tipo de Trabajo TOA';

    public $timestamps = true;
    protected $fillable = ['id_tipo', 'desc_tipo'];

    protected $guarded = [];

    protected $primaryKey = 'id_tipo';

    public $incrementing = false;

    public $modelFields = [
        'id_tipo' => [
            'label' => 'Tipo de rabajo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 30,
            'textoAyuda' => 'Tipo de trabajo. M&aacute;ximo 30 caracteres.',
            'esId' => true,
            'esObligatorio' => true,
            'esUnico' => true
        ],
        'desc_tipo' => [
            'label' => 'Descripci&oacute;n tipo de trabajo',
            'tipo' => OrmField::TIPO_CHAR,
            'largo' => 50,
            'textoAyuda' => 'Descripci&oacute;n del tipo de trabajo. M&aacute;ximo 50 caracteres.',
            'esObligatorio' => true,
            'esUnico' => true
        ],
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_trabajo_toa');
    }

    public function __toString()
    {
        return (string) $this->desc_tipo;
    }

    public function mostrarInfo()
    {
        $descripcionTrabajo = '';
        $tipos = ['A', '-', 'M', 'B', 'T'];

        if (strlen($this->id_tipo) === 10 and in_array(substr($this->id_tipo, 0, 1), $tipos)) {
            $tiposServicio = [
                'BA' => 0,
                'STB' => 2,
                'DTH' => 4,
                'VDSL' => 6,
                'IPTV' => 8,
            ];

            foreach ($tiposServicio as $servicio => $indice) {
                $valor = substr($this->id_tipo, $indice, 2);
                $descripcionTrabajo .= "<span class=\"label label-default\">{$servicio}</span>"
                    ."<span class=\"label label-info\">{$valor}</span>";
            }
        }

        return (string) empty($descripcionTrabajo) ? $this->id_tipo : $descripcionTrabajo;
    }
}
