<?php

namespace App\Http\Controllers\Toa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Orm\OrmController;

class ConfigController extends Controller
{
    use OrmController;

    protected $modelNameSpace = '\\App\\Toa\\';

    public function __construct()
    {
        $this->routeName  = 'toaConfig';
        $this->menuModulo = [
            'tecnicoToa' => ['nombre'=>trans('toa.config_menu_tecnico'), 'icono'=>'user'],
            'empresaToa' => ['nombre'=>trans('toa.config_menu_empresa'), 'icono'=>'home'],
            'tipMaterialTrabajoToa' => ['nombre'=>trans('toa.config_menu_tipo_material_trabajo'), 'icono'=>'object-group'],
            'tipoTrabajoToa' => ['nombre'=>trans('toa.config_menu_tipo_trabajo'), 'icono'=>'television'],
            'ciudadToa' => ['nombre'=>trans('toa.config_menu_ciudad'), 'icono'=>'map-marker'],
            'empresaCiudadToa' => ['nombre' => trans('toa.config_menu_empresa_ciudad'), 'icono'=>'map-marker'],
        ];

        $this->makeView();
    }
}
