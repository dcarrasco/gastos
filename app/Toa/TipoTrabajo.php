<?php

namespace App\Toa;

use App\OrmModel\OrmModel;
use App\OrmModel\OrmField\Text;

class TipoTrabajo extends OrmModel
{
    // Eloquent
    public $label = 'Tipo de Trabajo TOA';
    protected $fillable = ['id_tipo', 'desc_tipo'];
    protected $primaryKey = 'id_tipo';
    public $incrementing = false;

    // OrmModel
    public $title = 'desc_tipo';
    public $search = [
        'id_tipo', 'desc_tipo',
    ];
    public $modelOrder = 'id_tipo';


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_trabajo_toa');
    }

    public function fields()
    {
        return [
            Text::make('id', 'id_tipo')
                ->sortable()
                ->rules('max:30', 'required', 'unique'),

            Text::make('descripcion', 'desc_tipo')
                ->sortable()
                ->rules('max:50', 'required', 'unique'),
        ];
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
