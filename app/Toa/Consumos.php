<?php

namespace App\Toa;

use DB;
use App\Helpers\Reporte;
use App\Stock\ClaseMovimiento;

class Consumos
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
        return DB::table(DB::raw(config('invfija.bd_movimientos_sap_fija').' m'))
            ->where('m.fecha_contabilizacion', '>=', $fechaDesde)
            ->where('m.fecha_contabilizacion', '<=', $fechaHasta)
            ->whereIn('m.codigo_movimiento', ClaseMovimiento::transaccionesConsumoToa())
            ->whereIn('m.centro', static::CENTROS_CONSUMO);
    }

    protected static function getBaseSelectQuery()
    {
        return [
            DB::raw('\'ver peticiones\' as texto_link'),
            DB::raw('sum(-m.cantidad_en_um) as cant'),
            DB::raw('sum(-m.importe_ml) as monto'),
        ];
    }

    protected static function getBaseSelectSubQuery()
    {
        return [
            'm.referencia',
            DB::raw('sum(-m.cantidad_en_um) as cant'),
            DB::raw('sum(-m.importe_ml) as monto'),
        ];
    }

    protected static function getBuilderQuery($fromQuery, $fields)
    {
        $subQueryPrefix = 'q1';

        $fields = collect($fields)->map(function ($campo) use ($subQueryPrefix) {
            list($prefijo, $campo) = explode('.', $campo);
            return $subQueryPrefix.'.'.$campo;
        })->all();

        return DB::table(DB::raw('('.$fromQuery->toSql().') '.$subQueryPrefix))
            ->mergeBindings($fromQuery)
            ->select(array_merge($fields, [
                DB::raw('\'ver peticiones\' as texto_link'),
                DB::raw('count('.$subQueryPrefix.'.referencia) as referencia'),
                DB::raw('sum('.$subQueryPrefix.'.cant) as cant'),
                DB::raw('sum('.$subQueryPrefix.'.monto) as monto'),
            ]))
            ->groupBy($fields);
    }

    protected static function getDataReportePeticiones($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            'm.referencia',
            'm.carta_porte',
            'c.empresa',
            'm.cliente',
            'b.tecnico',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'm.cliente', '=', 'b.id_tecnico')
            ->leftJoin(DB::raw(config('invfija.bd_empresas_toa').' c'), 'm.vale_acomp', '=', 'c.id_empresa')
            ->select(array_merge($queryFields, static::getBaseSelectQuery()))
            ->groupBy($queryFields)
            ->orderBy('m.referencia')
            ->get();
    }

    protected static function getCamposReportePeticiones($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'referencia' => ['titulo' => 'Numero Peticion', 'tipo' => 'link', 'route' => 'toa.peticion'],
            'carta_porte' => ['titulo' => 'Tipo de trabajo', 'tipo' => 'texto'],
            'empresa' => ['titulo' => 'Empresa', 'tipo' => 'texto'],
            'cliente' => ['titulo' => 'Cod Tecnico', 'tipo' => 'texto'],
            'tecnico' => ['titulo' => 'Nombre Tecnico', 'tipo' => 'texto'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
        ];

        Reporte::setOrderCampos($camposReporte, 'referencia');

        return $camposReporte;
    }

    protected static function getDataReporteCiudades($fechaDesde, $fechaHasta)
    {
        $selectSubQuery = [
            'b.id_ciudad',
            'd.ciudad',
            'd.orden',
        ];

        $from = static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'm.cliente', '=', 'b.id_tecnico')
            ->leftJoin(DB::raw(config('invfija.bd_ciudades_toa').' d'), 'b.id_ciudad', '=', 'd.id_ciudad')
            ->select(array_merge($selectSubQuery, static::getBaseSelectSubQuery()))
            ->groupBy(array_merge($selectSubQuery, ['m.referencia']));

        return static::getBuilderQuery($from, $selectSubQuery)
            ->orderBy('q1.orden')
            ->get();
    }

    protected static function getCamposReporteCiudades($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'ciudad' => ['titulo' => 'Ciudad', 'tipo' => 'texto'],
            'referencia' => ['titulo' => 'Peticiones', 'tipo' => 'numero', 'class' => 'text-right'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['ciudades', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['id_ciudad']
            ],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteEmpresas($fechaDesde, $fechaHasta)
    {
        $selectSubQuery = [
            'c.empresa',
            'c.id_empresa',
        ];

        $from = static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'm.cliente', '=', 'b.id_tecnico')
            ->leftJoin(DB::raw(config('invfija.bd_empresas_toa').' c'), 'b.id_empresa', '=', 'c.id_empresa')
            ->select(array_merge($selectSubQuery, static::getBaseSelectSubQuery()))
            ->groupBy(array_merge($selectSubQuery, ['m.referencia']));

        return static::getBuilderQuery($from, $selectSubQuery)
            ->orderBy('q1.empresa')
            ->get();
    }

    protected static function getCamposReporteEmpresas($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'empresa' => ['titulo' => 'Empresa', 'tipo' => 'texto'],
            'referencia' => ['titulo' => 'Peticiones', 'tipo' => 'numero', 'class' => 'text-right'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['empresas', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['id_empresa']
            ],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteTecnicos($fechaDesde, $fechaHasta)
    {
        $selectSubQuery = [
            'c.empresa',
            'm.cliente',
            'b.tecnico',
        ];

        $from = static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'm.cliente', '=', 'b.id_tecnico')
            ->leftJoin(DB::raw(config('invfija.bd_empresas_toa').' c'), 'b.id_empresa', '=', 'c.id_empresa')
            ->select(array_merge($selectSubQuery, static::getBaseSelectSubQuery()))
            ->groupBy(array_merge($selectSubQuery, ['m.referencia']));

        return static::getBuilderQuery($from, $selectSubQuery)
            ->orderBy('q1.empresa')
            ->orderBy('q1.tecnico')
            ->get();
    }

    protected static function getCamposReporteTecnicos($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'empresa' => ['titulo' => 'Empresa', 'tipo' => 'texto'],
            'cliente' => ['titulo' => 'Cod Tecnico', 'tipo' => 'texto'],
            'tecnico' => ['titulo' => 'Nombre Tecnico', 'tipo' => 'texto'],
            'referencia' => ['titulo' => 'Peticiones', 'tipo' => 'numero', 'class' => 'text-right'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['tecnicos', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['cliente']
            ],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteTiposMaterial($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            'c.desc_tip_material',
            'm.ume',
            'b.id_tip_material_trabajo',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->leftJoin(DB::raw(config('invfija.bd_catalogo_tip_material_toa').' b'), 'm.material', '=', 'b.id_catalogo')
            ->leftJoin(
                DB::raw(config('invfija.bd_tip_material_trabajo_toa').' c'),
                'b.id_tip_material_trabajo',
                '=',
                'c.id'
            )
            ->select(array_merge($queryFields, static::getBaseSelectQuery()))
            ->groupBy($queryFields)
            ->orderBy('c.desc_tip_material')
            ->get();
    }

    protected static function getCamposReporteTiposMaterial($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'desc_tip_material' => ['titulo' => 'Tipo Material', 'tipo' => 'texto'],
            'ume' => ['titulo' => 'Unidad', 'tipo' => 'texto'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['tiposMaterial', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['id_tip_material_trabajo']
            ],
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
            ->leftJoin(DB::raw(config('invfija.bd_catalogo_tip_material_toa').' b'), 'm.material', '=', 'b.id_catalogo')
            ->leftJoin(
                DB::raw(config('invfija.bd_tip_material_trabajo_toa').' c'),
                'b.id_tip_material_trabajo',
                '=',
                'c.id'
            )
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
            'material' => ['titulo' => 'Cod Material', 'tipo' => 'texto'],
            'texto_material' => ['titulo' => 'Desc Material', 'tipo' => 'texto'],
            'ume' => ['titulo' => 'Unidad', 'tipo' => 'texto'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['materiales', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['material']
            ],
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
            'valor' => ['titulo' => 'Valor', 'tipo' => 'texto'],
            'lote' => ['titulo' => 'Lote', 'tipo' => 'texto'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['lotes', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['lote']
            ],
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
            'valor' => ['titulo' => 'Valor', 'tipo' => 'texto'],
            'lote' => ['titulo' => 'Lote', 'tipo' => 'texto'],
            'material' => ['titulo' => 'Cod Material', 'tipo' => 'texto'],
            'texto_material' => ['titulo' => 'Desc Material', 'tipo' => 'texto'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['lotesMateriales', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['lote', 'material']
            ],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReportePep($fechaDesde, $fechaHasta)
    {
        $queryFields = [
            'm.codigo_movimiento',
            'm.texto_movimiento',
            'm.elemento_pep',
        ];

        return static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->select(array_merge($queryFields, static::getBaseSelectQuery()))
            ->groupBy($queryFields)
            ->orderBy('m.codigo_movimiento')
            ->orderBy('m.elemento_pep')
            ->get();
    }

    protected static function getCamposReportePep($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'codigo_movimiento' => ['titulo' => 'CodMov', 'tipo' => 'texto'],
            'texto_movimiento' => ['titulo' => 'Desc Movimiento', 'tipo' => 'texto'],
            'elemento_pep' => ['titulo' => 'PEP', 'tipo' => 'texto'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['pep', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['codigo_movimiento', 'elemento_pep']
            ],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }

    protected static function getDataReporteTiposTrabajo($fechaDesde, $fechaHasta)
    {
        $selectSubQuery = [
            'm.carta_porte',
        ];

        $from = static::getDataReporteBase($fechaDesde, $fechaHasta)
            ->select(array_merge($selectSubQuery, static::getBaseSelectSubQuery()))
            ->groupBy(array_merge($selectSubQuery, ['m.referencia']));

        return static::getBuilderQuery($from, $selectSubQuery)
            ->orderBy('q1.carta_porte')
            ->get();
    }

    protected static function getCamposReporteTiposTrabajo($fechaDesde, $fechaHasta)
    {
        $camposReporte = [
            'carta_porte' => ['titulo' => 'Tipo de trabajo', 'tipo' => 'texto'],
            'referencia' => ['titulo' => 'Peticiones', 'tipo' => 'numero', 'class' => 'text-right'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
            'texto_link' => [
                'titulo' => '',
                'tipo' => 'link_registro',
                'class' => 'text-right',
                'route' => 'toa.peticiones',
                'routeFixedParams' => ['tiposTrabajo', $fechaDesde, $fechaHasta],
                'routeVariableParams' => ['carta_porte']
            ],
        ];

        Reporte::setOrderCampos($camposReporte, 'ciudad');

        return $camposReporte;
    }
}
