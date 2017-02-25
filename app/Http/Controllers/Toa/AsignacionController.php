<?php

namespace App\Http\Controllers\Toa;

use App\Toa\Asignaciones;
use App\Http\Controllers\Controller;
use App\Http\Requests\Toa\AsignacionesRequest;

class AsignacionController extends Controller
{
    protected $reportes = [
        'tecnicos'        => 'Tecnicos',
        'materiales'      => 'Materiales',
        'lotes'           => 'Lotes',
        'lotesMateriales' => 'Lotes y materiales',
        'detalle'         => 'Detalle todos los registros',
    ];

    protected $reporte = null;

    public function showForm()
    {
        $reportes = $this->reportes;
        $reporte = $this->reporte;

        return view('toa.consumos', compact('reportes', 'reporte'));
    }

    public function getAsignacion(AsignacionesRequest $request)
    {
        $this->reporte = Asignaciones::getReporte($request->reporte, $request->fecha_desde, $request->fecha_hasta);

        return $this->showForm();
    }

    public function peticiones($tipo, $fechaDesde, $fechaHasta, $id, $id2 = null)
    {
        $peticiones = Peticiones::getPeticiones($tipo, $fechaDesde, $fechaHasta, $id, $id2);

        $map = new GoogleMaps(['mapCss' => 'height: 350px']);

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

    public function peticion($idPeticion = null)
    {
        $idPeticion = empty($idPeticion) ? request('peticion') : $idPeticion;

        $peticion = Peticiones::peticion($idPeticion);

        $map = new GoogleMaps(['mapCss' => 'height: 350px']);

        if (count($peticion) and count($peticion['peticionToa'])) {
            $map->addMarker([
                'lat'   => array_get($peticion, 'peticionToa.acoord_y'),
                'lng'   => array_get($peticion, 'peticionToa.acoord_x'),
                'title' => array_get($peticion, 'peticionToa.cname'),
            ]);
        }

        $googleMap = $map->createMap();

        $tipoTrabajo = new TipoTrabajoToa();
        $tipoTrabajo = new TipoTrabajoToa(['id_tipo'=>strtoupper(array_get($peticion, 'peticionToa.XA_WORK_TYPE'))]);

        // set_message(($idPeticion and ! $peticiones) ? $this->lang->line('toa_consumo_peticion_not_found') : '');

        return view('toa.detalle_peticion', compact('peticion', 'googleMap', 'tipoTrabajo'));
    }
}
