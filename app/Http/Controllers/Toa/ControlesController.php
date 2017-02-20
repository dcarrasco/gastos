<?php

namespace App\Http\Controllers\Toa;

use App\Http\Controllers\Controller;
use App\Http\Requests\ControlesToaRequest;
use App\Http\Controllers\Toa\ModulosControles;
use App\Toa\EmpresaToa;
use App\Toa\ControlesToa;
use App\Stock\ClaseMovimiento;

class ControlesController extends Controller
{

    use ModulosControles;

    protected $control = null;
    protected $controlCampos = null;

    public function showFormControles($tipo = null)
    {
        $moduloSelected = empty($tipo) ? collect(array_keys($this->menuModulo))->first() : $tipo;

        $empresas      = EmpresaToa::getModelFormOptions();
        $transacciones = array_merge(
            ['000' => 'Todos los movimientos'],
            ClaseMovimiento::getModelFormOptions(['cmv'=>ClaseMovimiento::transaccionesConsumoToa()])->all()
        );
        $unidadesConsumo = ControlesToa::getUnidadesConsumo($moduloSelected);

        $control = $this->control;
        $controlCampos = $this->controlCampos;

        return view('toa.controles', compact('moduloSelected', 'empresas', 'transacciones', 'unidadesConsumo', 'control', 'controlCampos'));
    }

    public function getControles(ControlesToaRequest $request, $tipo = null)
    {
        $moduloSelected = empty($tipo) ? collect(array_keys($this->menuModulo))->first() : $tipo;

        $this->control = ControlesToa::{'control'.ucfirst($moduloSelected)}($request);
        $this->controlCampos = ControlesToa::{'control'.ucfirst($moduloSelected).'Campos'}();

        return $this->showFormControles($tipo);
    }
}
