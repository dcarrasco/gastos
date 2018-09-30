<?php

namespace App\Http\Controllers\Toa;

use App\OrmModel\Toa\Ciudad;
use Illuminate\Http\Request;
use App\OrmModel\Toa\Empresa;
use App\OrmModel\Toa\Tecnico;
use App\OrmModel\Toa\TipoTrabajo;
use App\OrmModel\Toa\EmpresaCiudad;
use App\Http\Controllers\Controller;
use App\OrmModel\Toa\TipMaterialTrabajo;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    public function __construct()
    {
        $this->routeName  = 'toaConfig';
        $this->menuModulo = [
            Tecnico::class,
            Empresa::class,
            TipMaterialTrabajo::class,
            TipoTrabajo::class,
            Ciudad::class,
            EmpresaCiudad::class,
        ];

        $this->makeView();
    }
}
