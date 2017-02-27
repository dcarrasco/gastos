<?php

namespace App\Inventario;

use App\Helpers\Reporte;

trait ReportesInventario
{
    protected function getCampo($campo)
    {
        $campos = [
            'hoja'      => ['titulo' => 'Hoja', 'tipo' => 'link', 'route' => 'inventario.reporte'],
            'auditor'   => ['titulo' => 'Auditor'],
            'digitador' => ['titulo' => 'Digitador'],

            'catalogo'    => ['titulo' => 'Catalogo', 'tipo' => 'link', 'href' => 'inventario.reporte'],
            'descripcion' => ['titulo' => 'Descripcion'],
            'um'          => ['titulo' => 'UM'],
            'pmp'         => ['titulo' => 'PMP', 'class' => 'text-center', 'tipo' => 'valor_pmp'],

            'ubicacion'      => ['titulo' => 'Ubicacion'],
            'tipo_ubicacion' => ['titulo' => 'Tipo de Ubicacion'],

            'lote'         => ['titulo' => 'Lote'],
            'centro'       => ['titulo' => 'Centro'],
            'almacen'      => ['titulo' => 'Almacen'],
            'tipo_ajuste'  => ['titulo' => 'Tipo Dif'],
            'glosa_ajuste' => ['titulo' => 'Observacion'],

            'sum_stock_sap'    => ['titulo' => 'Cant SAP', 'class' => 'text-center', 'tipo' => 'numero'],
            'sum_stock_fisico' => ['titulo' => 'Cant Fisico', 'class' => 'text-center', 'tipo' => 'numero'],
            'sum_stock_ajuste' => ['titulo' => 'Cant Ajuste', 'class' => 'text-center', 'tipo' => 'numero'],
            'sum_stock_diff'   => ['titulo' => 'Cant Dif', 'class' => 'text-center', 'tipo' => 'numero_dif'],

            'sum_valor_sap'    => ['titulo' => 'Valor SAP', 'class' => 'text-center', 'tipo' => 'valor'],
            'sum_valor_fisico' => ['titulo' => 'Valor Fisico', 'class' => 'text-center', 'tipo' => 'valor'],
            'sum_valor_ajuste' => ['titulo' => 'Valor Ajuste', 'class' => 'text-center', 'tipo' => 'valor'],
            'sum_valor_diff'   => ['titulo' => 'Valor Dif', 'class' => 'text-center', 'tipo' => 'valor_dif'],

            'q_faltante'    => ['titulo'=>'Cant Faltante', 'class'=>'text-center', 'tipo'=>'numero'],
            'q_sobrante'    => ['titulo'=>'Cant Sobrante', 'class'=>'text-center', 'tipo'=>'numero'],
            'q_coincidente' => ['titulo'=>'Cant Coincidente', 'class'=>'text-center', 'tipo'=>'numero'],
            'v_faltante'    => ['titulo'=>'Valor Faltante', 'class'=>'text-center', 'tipo'=>'valor'],
            'v_sobrante'    => ['titulo'=>'Valor Sobrante', 'class'=>'text-center', 'tipo'=>'valor'],
            'v_coincidente' => ['titulo'=>'Valor Coincidente', 'class'=>'text-center', 'tipo'=>'valor'],
        ];

        return array_get($campos, $campo, []);
    }


    public function getDetalleHoja($hoja = null)
    {
        if (empty($hoja)) {
            return null;
        }

        return DetalleInventario::where('id_inventario', $this->id)
            ->where('hoja', $hoja)->get();
    }

    public function reporte($tipo)
    {
        $reporte = new Reporte(
            $this->{'reporte'.ucfirst($tipo)}(),
            $this->{'camposReporte'.ucfirst($tipo)}()
        );

        return $reporte->make();
    }

    protected function selectFieldsCantidades()
    {
        $inclAjuste = request('incl_ajustes') ? ' + d.stock_ajuste' : '';

        return [
            \DB::raw('sum(d.stock_sap) as sum_stock_sap'),
            \DB::raw('sum(d.stock_fisico) as sum_stock_fisico'),
            \DB::raw('sum(d.stock_ajuste) as sum_stock_ajuste'),
            \DB::raw('sum(d.stock_fisico-d.stock_sap'.$inclAjuste.') as sum_stock_diff'),
            \DB::raw('sum(d.stock_sap*c.pmp) as sum_valor_sap'),
            \DB::raw('sum(d.stock_fisico*c.pmp) as sum_valor_fisico'),
            \DB::raw('sum(d.stock_ajuste*c.pmp) as sum_valor_ajuste'),
            \DB::raw('sum((d.stock_fisico-d.stock_sap'.$inclAjuste.')*c.pmp) as sum_valor_diff'),
        ];
    }

    protected function camposCantidades()
    {
        $camposCantidades = [];
        $camposCantidades['sum_stock_sap'] = $this->getCampo('sum_stock_sap');
        $camposCantidades['sum_stock_fisico'] = $this->getCampo('sum_stock_fisico');
        if (request('incl_ajustes')) {
            $camposCantidades['sum_stock_ajuste'] = $this->getCampo('sum_stock_ajuste');
        }
        $camposCantidades['sum_stock_diff'] = $this->getCampo('sum_stock_diff');

        $camposCantidades['sum_valor_sap'] = $this->getCampo('sum_valor_sap');
        $camposCantidades['sum_valor_fisico'] = $this->getCampo('sum_valor_fisico');
        if (request('incl_ajustes')) {
            $camposCantidades['sum_valor_ajuste'] = $this->getCampo('sum_valor_ajuste');
        }
        $camposCantidades['sum_valor_diff'] = $this->getCampo('sum_valor_diff');

        return $camposCantidades;
    }

    protected function queryBaseReporteInventario($selectFields = [], $groupByFields = [])
    {
        return \DB::table(\DB::raw(config('invfija.bd_detalle_inventario').' as d'))
            ->where('id_inventario', $this->id)
            ->leftJoin(\DB::raw(config('invfija.bd_catalogos').' as c'), 'd.catalogo', '=', 'c.catalogo')
            ->select($selectFields)
            ->groupBy($groupByFields);
    }

    protected function camposReporte($campos = [])
    {
        $camposReporte = [];
        foreach ($campos as $campo) {
            $camposReporte[$campo] = $this->getCampo($campo);
        }

        return $camposReporte;
    }

    public function reporteHoja()
    {
        $selectFields = array_merge(
            ['d.hoja', \DB::raw('a.nombre as auditor'), \DB::raw('u.nombre as digitador')],
            $this->selectFieldsCantidades()
        );
        $groupByFields = ['d.hoja', 'a.nombre', 'u.nombre'];

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->leftJoin(\DB::raw(config('invfija.bd_auditores').' as a'), 'd.auditor', '=', 'a.id')
            ->leftJoin(\DB::raw(config('invfija.bd_usuarios').' as u'), 'd.digitador', '=', 'u.id')
            ->orderBy('hoja')
            ->get();
    }

    public function camposReporteHoja()
    {
        $campos = ['hoja', 'auditor', 'digitador'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        Reporte::setOrderCampos($campos, 'hoja');

        return $campos;
    }

    public function reporteMaterial()
    {
        $reporteFields = ['d.catalogo', 'd.descripcion', 'd.um', 'c.pmp'];

        $selectFields = array_merge($reporteFields, $this->selectFieldsCantidades());
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('catalogo')
            ->get();
    }

    public function camposReporteMaterial()
    {
        $campos = ['catalogo', 'descripcion', 'um', 'pmp'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        Reporte::setOrderCampos($campos, 'catalogo');

        return $campos;
    }

    public function reporteMaterialFaltante()
    {
        $inclAjuste = request('incl_ajustes') ? ' + d.stock_ajuste' : '';

        $reporteFields = ['d.catalogo', 'd.descripcion', 'd.um', 'c.pmp'];

        $selectFields = array_merge($reporteFields, [
            \DB::raw('(SUM(stock_sap) - 0.5 * (SUM(stock_sap + stock_fisico'.$inclAjuste.') - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as q_faltante'),
            \DB::raw('(0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as q_coincidente'),
            \DB::raw('(SUM((stock_fisico'.$inclAjuste.')) - 0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as q_sobrante'),
            \DB::raw('c.pmp * (SUM(stock_sap) - 0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as v_faltante'),
            \DB::raw('c.pmp * (0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as v_coincidente'),
            \DB::raw('c.pmp * (SUM((stock_fisico'.$inclAjuste.')) - 0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as v_sobrante'),
        ]);
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('catalogo')
            ->get();
    }

    public function camposReporteMaterialFaltante()
    {
        $campos = $this->camposReporte([
            'catalogo', 'descripcion', 'um', 'pmp',
            'q_faltante', 'q_coincidente', 'q_sobrante',
            'v_faltante', 'v_coincidente', 'v_sobrante'
        ]);
        Reporte::setOrderCampos($campos, 'catalogo');

        return $campos;
    }

    public function reporteUbicacion()
    {
        $reporteFields = ['d.ubicacion'];

        $selectFields = array_merge($reporteFields, $this->selectFieldsCantidades());
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('d.ubicacion')
            ->get();
    }

    public function camposReporteUbicacion()
    {
        $campos = ['ubicacion'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        Reporte::setOrderCampos($campos, 'catalogo');

        return $campos;
    }

    public function reporteTiposUbicacion()
    {
        $reporteFields = ['t.tipo_ubicacion', 'd.ubicacion'];

        $selectFields = array_merge($reporteFields, $this->selectFieldsCantidades());
        $groupByFields = $reporteFields;

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->leftJoin(\DB::raw(config('invfija.bd_inventarios').' as i'), 'd.id_inventario', '=', 'i.id')
            ->leftJoin(\DB::raw(config('invfija.bd_ubic_tipoubic').' as ut'), function ($join) {
                $join->on('d.ubicacion', '=', 'ut.ubicacion');
                $join->on('i.tipo_inventario', '=', 'ut.tipo_inventario');
            })
            ->leftJoin(\DB::raw(config('invfija.bd_tipo_ubicacion').' as t'), 'ut.id_tipo_ubicacion', '=', 't.id')
            ->orderBy('t.tipo_ubicacion')
            ->get();
    }

    public function camposReporteTiposUbicacion()
    {
        $campos = ['tipo_ubicacion', 'ubicacion'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        Reporte::setOrderCampos($campos, 'tipo_ubicacion');

        return $campos;
    }

    public function reporteAjustes()
    {
        $reporteFields = ['d.catalogo', 'd.descripcion', 'd.lote', 'd.centro', 'd.almacen', 'd.ubicacion', 'd.hoja', 'd.um', 'd.glosa_ajuste'];

        $queryTipoAjuste = 'CASE WHEN (stock_fisico-stock_sap+stock_ajuste) > 0 THEN \'SOBRANTE\' WHEN (stock_fisico-stock_sap+stock_ajuste) < 0 THEN \'FALTANTE\' ELSE \'OK\' END';

        $selectFields = array_merge(
            array_merge($reporteFields, [\DB::raw($queryTipoAjuste.' as tipo_ajuste')]),
            $this->selectFieldsCantidades()
        );
        $groupByFields = array_merge($reporteFields, [\DB::raw($queryTipoAjuste)]);

        return $this->queryBaseReporteInventario($selectFields, $groupByFields)
            ->orderBy('d.catalogo')
            ->get();
    }

    public function camposReporteAjustes()
    {
        $campos = ['catalogo', 'descripcion', 'lote', 'centro', 'almacen', 'ubicacion', 'hoja', 'um'];
        $campos = array_merge($this->camposReporte($campos), $this->camposCantidades());
        $campos['tipo_ajuste'] = $this->getCampo('tipo_ajuste');
        $campos['glosa_ajuste'] = $this->getCampo('glosa_ajuste');

        unset($campos['sum_valor_sap']);
        unset($campos['sum_valor_fisico']);
        unset($campos['sum_valor_diff']);

        Reporte::setOrderCampos($campos, 'catalogo');

        return $campos;
    }
}
