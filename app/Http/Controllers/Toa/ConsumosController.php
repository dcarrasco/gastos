<?php

namespace App\Http\Controllers\Toa;

use App\Toa\Consumos;
use App\Toa\Peticiones;
use App\Helpers\GoogleMaps;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConsumosToaRequest;

class ConsumosController extends Controller
{
    protected $reportes = [
        'peticiones'      => 'Numero peticion',
        'empresas'        => 'Empresas',
        'ciudades'        => 'Ciudades',
        'tecnicos'        => 'Tecnicos',
        'tiposMaterial'   => 'Tipos de material',
        'materiales'      => 'Materiales',
        'lotes'           => 'Lotes',
        'lotesMateriales' => 'Lotes y materiales',
        'pep'             => 'PEP',
        'tiposTrabajo'    => 'Tipo de trabajo',
        'detalles'        => 'Detalle todos los registros',
    ];

    protected $reporteConsumo = null;

    public function showFormConsumos()
    {
        $reportes = $this->reportes;
        $reporteConsumo = $this->reporteConsumo;

        return view('toa.consumos', compact('reportes', 'reporteConsumo'));
    }

    public function getConsumos(ConsumosToaRequest $request)
    {
        $this->reporteConsumo = Consumos::getReporte($request->reporte, $request->fecha_desde, $request->fecha_hasta);

        return $this->showFormConsumos();
    }

    public function peticiones($tipo, $fechaDesde, $fechaHasta, $id, $id2 = null)
    {
        $peticiones = Peticiones::getPeticiones($tipo, $fechaDesde, $fechaHasta, $id, $id2);

        $map = new GoogleMaps([
            'mapCss' => 'height: 350px',
        ]);

        $peticiones->each(function ($peticion) use (&$map) {
            $map->addMarker([
                'lat'   => $peticion->acoord_y,
                'lng'   => $peticion->acoord_x,
                'title' => "{$peticion->empresa} - {$peticion->tecnico} - {$peticion->referencia}",
            ]);
        });

        $reportePeticiones = Peticiones::getReporte($peticiones);
        $googleMaps        = $map->createMap();

        return view('toa.peticiones', compact('reportePeticiones', 'googleMaps'));
    }
}
