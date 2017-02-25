<?php

namespace App\Toa;

use App\Stock\ClaseMovimiento;
use App\Helpers\Reporte;

class Asignaciones
{
    const CENTROS_CONSUMO = ['CH32', 'CH33'];

    public static function getReporte($nombreReporte = null, $fechaDesde = null, $fechaHasta)
    {
        $datosReporte  = static::{'getDataReporte'.ucfirst($nombreReporte)}($fechaDesde, $fechaHasta);
        $camposReporte = static::{'getCamposReporte'.ucfirst($nombreReporte)}($fechaDesde, $fechaHasta);

        $reporte = new Reporte($datosReporte, $camposReporte);

        return $reporte->make();
    }

    protected static function getDataReporteBase($fechaDesde, $fechaHasta)
    {
        return \DB::table(\DB::raw(config('invfija.bd_movimientos_sap_fija').' m'))
            ->where('m.fecha_contabilizacion', '>=', $fechaDesde)
            ->where('m.fecha_contabilizacion', '<=', $fechaHasta)
            ->whereIn('m.codigo_movimiento', ClaseMovimiento::transaccionesAsignacionToa())
            ->whereIn('m.centro', static::CENTROS_CONSUMO)
            ->where('m.signo', 'NEG');
}

    protected static function getBaseSelectQuery()
    {
        return [
            \DB::raw('\'ver asignaciones\' as texto_link'),
            \DB::raw('sum(-m.cantidad_en_um) as cant'),
            \DB::raw('sum(-m.importe_ml) as monto'),
        ];
    }

    protected static function getDataReporteTecnicos($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            'c.empresa',
            'm.cliente',
            'b.tecnico',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), \DB::raw('m.cliente collate Latin1_General_CI_AS'), '=', \DB::raw('b.id_tecnico collate Latin1_General_CI_AS'))
            ->leftJoin(\DB::raw(config('invfija.bd_empresas_toa').' c'), 'b.id_empresa', '=', 'c.id_empresa')
            ->select(array_merge($queryFields, static::getBaseSelectQuery()))
            ->groupBy($queryFields)
            ->orderBy('c.empresa')
            ->get();
    }

    protected static function getCamposReporteTecnicos($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'empresa'    => ['titulo' => 'Empresa', 'tipo' => 'texto'],
            'cliente'    => ['titulo' => 'Cod Tecnico', 'tipo' => 'texto'],
            'tecnico'    => ['titulo' => 'Nombre Tecnico', 'tipo' => 'texto'],
            'cant'       => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto'      => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => ['titulo' => '', 'tipo' => 'link_registro', 'class' => 'text-right', 'route' => 'toa.peticiones', 'routeFixedParams' => ['tecnicos', $fechaDesde, $fechaHasta], 'routeVariableParams' => ['cliente']],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteMateriales($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            'c.desc_tip_material',
            'm.material',
            'm.texto_material',
            'm.ume',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(\DB::raw(config('invfija.bd_catalogo_tip_material_toa').' b'), 'm.material', '=', 'b.id_catalogo')
            ->leftJoin(\DB::raw(config('invfija.bd_tip_material_trabajo_toa').' c'), 'b.id_tip_material_trabajo', '=', 'c.id')
            ->select(array_merge($queryFields, static::getBaseSelectQuery()))
            ->groupBy($queryFields)
            ->orderBy('c.desc_tip_material')
            ->orderBy('m.material')
            ->get();
    }

    protected static function getCamposReporteMateriales($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'desc_tip_material' => ['titulo' => 'Tipo Material', 'tipo' => 'texto'],
            'material'       => ['titulo' => 'Cod Material', 'tipo' => 'texto'],
            'texto_material' => ['titulo' => 'Desc Material', 'tipo' => 'texto'],
            'ume'            => ['titulo' => 'Unidad', 'tipo' => 'texto'],
            'cant'           => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto'          => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link'     => ['titulo' => '', 'tipo' => 'link_registro', 'class' => 'text-right', 'route' => 'toa.peticiones', 'routeFixedParams' => ['materiales', $fechaDesde, $fechaHasta], 'routeVariableParams' => ['material']],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteLotes($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            'm.valor',
            'm.lote',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->select(array_merge($queryFields, static::getBaseSelectQuery()))
            ->groupBy($queryFields)
            ->orderBy('m.valor')
            ->orderBy('m.lote')
            ->get();
    }

    protected static function getCamposReporteLotes($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'valor'      => ['titulo' => 'Valor', 'tipo' => 'texto'],
            'lote'       => ['titulo' => 'Lote', 'tipo' => 'texto'],
            'cant'       => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto'      => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => ['titulo' => '', 'tipo' => 'link_registro', 'class' => 'text-right', 'route' => 'toa.peticiones', 'routeFixedParams' => ['lotes', $fechaDesde, $fechaHasta], 'routeVariableParams' => ['lote']],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteLotesMateriales($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            'm.valor',
            'm.lote',
            'm.material',
            'm.texto_material',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->select(array_merge($queryFields, static::getBaseSelectQuery()))
            ->groupBy($queryFields)
            ->orderBy('m.valor')
            ->orderBy('m.lote')
            ->orderBy('m.material')
            ->get();
    }

    protected static function getCamposReporteLotesMateriales($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'valor'          => ['titulo' => 'Valor', 'tipo' => 'texto'],
            'lote'           => ['titulo' => 'Lote', 'tipo' => 'texto'],
            'material'       => ['titulo' => 'Cod Material', 'tipo' => 'texto'],
            'texto_material' => ['titulo' => 'Desc Material', 'tipo' => 'texto'],
            'cant'           => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto'          => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link'     => ['titulo' => '', 'tipo' => 'link_registro', 'class' => 'text-right', 'route' => 'toa.peticiones', 'routeFixedParams' => ['lotesMateriales', $fechaDesde, $fechaHasta], 'routeVariableParams' => ['lote', 'material']],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteDetalle($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            \DB::raw('m.fecha_contabilizacion as fecha'),
            'm.referencia',
            'c.empresa',
            'm.cliente',
            'b.tecnico',
            'm.codigo_movimiento',
            'm.texto_movimiento',
            'm.elemento_pep',
            'm.documento_material',
            'm.centro',
            'm.material',
            'm.texto_material',
            'm.lote',
            'm.valor',
            'm.umb',
            \DB::raw('(-m.cantidad_en_um) as cant'),
            \DB::raw('(-m.importe_ml) as monto'),
            'm.usuario',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), \DB::raw('m.cliente collate Latin1_General_CI_AS'), '=', \DB::raw('b.id_tecnico collate Latin1_General_CI_AS'))
            ->leftJoin(\DB::raw(config('invfija.bd_empresas_toa').' c'), 'b.id_empresa', '=', 'c.id_empresa')
            ->select($queryFields)
            ->orderBy('m.referencia')
            ->get();
    }

    protected static function getCamposReporteDetalle($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'fecha'              => ['titulo' => 'Fecha', 'tipo' => 'fecha'],
            'referencia'         => ['titulo' => 'Numero Peticion', 'tipo' => 'texto'],
            'empresa'            => ['titulo' => 'Empresa', 'tipo' => 'texto'],
            'cliente'            => ['titulo' => 'Cod Tecnico', 'tipo' => 'texto'],
            'tecnico'            => ['titulo' => 'Nombre Tecnico', 'tipo' => 'texto'],
            'codigo_movimiento'  => ['titulo' => 'Cod Movimiento', 'tipo' => 'texto'],
            'texto_movimiento'   => ['titulo' => 'Desc Movimiento', 'tipo' => 'texto'],
            'elemento_pep'       => ['titulo' => 'PEP', 'tipo' => 'texto'],
            'documento_material' => ['titulo' => 'Documento SAP', 'tipo' => 'texto'],
            'centro'             => ['titulo' => 'Centro', 'tipo' => 'texto'],
            'material'           => ['titulo' => 'Cod material', 'tipo' => 'texto'],
            'texto_material'     => ['titulo' => 'Desc material', 'tipo' => 'texto'],
            'lote'               => ['titulo' => 'Lote', 'tipo' => 'texto'],
            'valor'              => ['titulo' => 'Valor', 'tipo' => 'texto'],
            'umb'                => ['titulo' => 'Unidad', 'tipo' => 'texto'],
            'cant'               => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto'              => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'usuario'            => ['titulo' => 'Usuario SAP', 'tipo' => 'texto'],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

}
