<?php

namespace App\Toa;

use Illuminate\Database\Eloquent\Model;

class TipoTrabajo extends Model
{
    protected $fillable = ['id_tipo', 'desc_tipo'];
    protected $primaryKey = 'id_tipo';
    public $incrementing = false;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('invfija.bd_tipos_trabajo_toa');
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
