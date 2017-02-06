<?php

namespace App;

use App\Helpers\Reporte;

trait ReportesInventario
{
    protected function getCampo($campo)
    {
        $campos = [
            'hoja' => ['titulo' => 'Hoja', 'tipo' => 'link', 'href' => '/listado/detalle_hoja/'],
            'auditor' => ['titulo' => 'Auditor'],
            'digitador' => ['titulo' => 'Digitador'],

            'catalogo' => ['titulo' => 'Catalogo', 'tipo' => 'link', 'href' => '/listado/detallematerial'],
            'descripcion' => ['titulo' => 'Descripcion'],
            'um' => ['titulo' => 'UM'],
            'pmp' => ['titulo' => 'PMP', 'class' => 'text-center', 'tipo' => 'valor_pmp'],

            'sum_stock_sap' => ['titulo' => 'Cant SAP', 'class' => 'text-center', 'tipo' => 'numero'],
            'sum_stock_fisico' => ['titulo' => 'Cant Fisico', 'class' => 'text-center', 'tipo' => 'numero'],
            'sum_stock_ajuste' => ['titulo' => 'Cant Ajuste', 'class' => 'text-center', 'tipo' => 'numero'],
            'sum_stock_diff' => ['titulo' => 'Cant Dif', 'class' => 'text-center', 'tipo' => 'numero_dif'],

            'sum_valor_sap' => ['titulo' => 'Valor SAP', 'class' => 'text-center', 'tipo' => 'valor'],
            'sum_valor_fisico' => ['titulo' => 'Valor Fisico', 'class' => 'text-center', 'tipo' => 'valor'],
            'sum_valor_ajuste' => ['titulo' => 'Valor Ajuste', 'class' => 'text-center', 'tipo' => 'valor'],
            'sum_valor_diff' => ['titulo' => 'Valor Dif', 'class' => 'text-center', 'tipo' => 'valor_dif'],

            'q_faltante' => ['titulo'=>'Cant Faltante', 'class'=>'text-center', 'tipo'=>'numero'],
            'q_sobrante' => ['titulo'=>'Cant Sobrante', 'class'=>'text-center', 'tipo'=>'numero'],
            'q_coincidente' => ['titulo'=>'Cant Coincidente', 'class'=>'text-center', 'tipo'=>'numero'],
            'v_faltante' => ['titulo'=>'Valor Faltante', 'class'=>'text-center', 'tipo'=>'valor'],
            'v_sobrante' => ['titulo'=>'Valor Sobrante', 'class'=>'text-center', 'tipo'=>'valor'],
            'v_coincidente' => ['titulo'=>'Valor Coincidente', 'class'=>'text-center', 'tipo'=>'valor'],
        ];

        $definicionCampo = array_key_exists($campo, $campos) ? $campos[$campo] : [];

        if (!array_key_exists('titulo', $definicionCampo)) {
            $definicionCampo['titulo'] = '';
        }

        if (!array_key_exists('class', $definicionCampo)) {
            $definicionCampo['class'] = '';
        }

        if (!array_key_exists('tipo', $definicionCampo)) {
            $definicionCampo['tipo'] = 'texto';
        }

        return $definicionCampo;
    }


    public function getDetalleHoja($hoja = null)
    {
        if (empty($hoja)) {
            return null;
        }

        return DetalleInventario::where('id_inventario', '=', $this->id)
            ->where('hoja', '=', $hoja)->get();
    }

    public function reporte($tipo)
    {
        $reporte = new Reporte($this->{'reporte'.ucfirst($tipo)}(), $this->{'camposReporte'.ucfirst($tipo)}());

        return $reporte->make();
    }

    public function reporteHoja()
    {
        $inclAjuste = request()->input('incl_ajustes') ? ' + d.stock_ajuste' : '';

        $selectFields = [
            'd.hoja',
            \DB::raw('a.nombre as auditor'),
            \DB::raw('u.nombre as digitador'),
            \DB::raw('sum(d.stock_sap) as sum_stock_sap'),
            \DB::raw('sum(d.stock_fisico) as sum_stock_fisico'),
            \DB::raw('sum(d.stock_ajuste) as sum_stock_ajuste'),
            \DB::raw('sum(d.stock_fisico-d.stock_sap'.$inclAjuste.') as sum_stock_diff'),
            \DB::raw('sum(d.stock_sap*c.pmp) as sum_valor_sap'),
            \DB::raw('sum(d.stock_fisico*c.pmp) as sum_valor_fisico'),
            \DB::raw('sum(d.stock_ajuste*c.pmp) as sum_valor_ajuste'),
            \DB::raw('sum((d.stock_fisico-d.stock_sap'.$inclAjuste.')*c.pmp) as sum_valor_diff'),
        ];

        $groupByFields = ['hoja', 'a.nombre', 'u.nombre'];

        return \DB::table(\DB::raw(config('invfija.bd_detalle_inventario').' as d'))
            ->where('id_inventario', '=', $this->id)
            ->leftJoin(\DB::raw(config('invfija.bd_auditores').' as a'), 'd.auditor', '=', 'a.id')
            ->leftJoin(\DB::raw(config('invfija.bd_usuarios').' as u'), 'd.digitador', '=', 'u.id')
            ->leftJoin(\DB::raw(config('invfija.bd_catalogos').' as c'), 'd.catalogo', '=', 'c.catalogo')
            ->select($selectFields)
            ->groupBy($groupByFields)
            ->orderBy('hoja')
            ->get();
    }

    public function camposReporteHoja()
    {
        $campos = [];

        $campos['hoja']      = $this->getCampo('hoja');
        $campos['auditor']   = $this->getCampo('auditor');
        $campos['digitador'] = $this->getCampo('digitador');

        $campos['sum_stock_sap'] = $this->getCampo('sum_stock_sap');
        $campos['sum_stock_fisico'] = $this->getCampo('sum_stock_fisico');
        if (request()->input('incl_ajustes')) {
            $campos['sum_stock_ajuste'] = $this->getCampo('sum_stock_ajuste');
        }
        $campos['sum_stock_diff'] = $this->getCampo('sum_stock_diff');

        $campos['sum_valor_sap'] = $this->getCampo('sum_valor_sap');
        $campos['sum_valor_fisico'] = $this->getCampo('sum_valor_fisico');
        if (request()->input('incl_ajustes')) {
            $campos['sum_valor_ajuste'] = $this->getCampo('sum_valor_ajuste');
        }
        $campos['sum_valor_diff'] = $this->getCampo('sum_valor_diff');

        Reporte::setOrderCampos($campos, 'hoja');

        return $campos;
    }

    public function reporteMaterial()
    {
        $inclAjuste = request()->input('incl_ajustes') ? ' + d.stock_ajuste' : '';

        $selectFields = [
            'd.catalogo',
            'd.descripcion',
            'd.um',
            'c.pmp',
            \DB::raw('sum(d.stock_sap) as sum_stock_sap'),
            \DB::raw('sum(d.stock_fisico) as sum_stock_fisico'),
            \DB::raw('sum(d.stock_ajuste) as sum_stock_ajuste'),
            \DB::raw('sum(d.stock_fisico-d.stock_sap'.$inclAjuste.') as sum_stock_diff'),
            \DB::raw('sum(d.stock_sap*c.pmp) as sum_valor_sap'),
            \DB::raw('sum(d.stock_fisico*c.pmp) as sum_valor_fisico'),
            \DB::raw('sum(d.stock_ajuste*c.pmp) as sum_valor_ajuste'),
            \DB::raw('sum((d.stock_fisico-d.stock_sap'.$inclAjuste.')*c.pmp) as sum_valor_diff'),
        ];

        $groupByFields = ['catalogo', 'descripcion', 'um', 'pmp'];

        return \DB::table(\DB::raw(config('invfija.bd_detalle_inventario').' as d'))
            ->where('id_inventario', '=', $this->id)
            ->leftJoin(\DB::raw(config('invfija.bd_catalogos').' as c'), 'd.catalogo', '=', 'c.catalogo')
            ->select($selectFields)
            ->groupBy($groupByFields)
            ->orderBy('catalogo')
            ->get();
    }

    public function camposReporteMaterial()
    {
        $campos = [];

        $campos['catalogo']    = $this->getCampo('catalogo');
        $campos['descripcion'] = $this->getCampo('descripcion');
        $campos['um']          = $this->getCampo('um');
        $campos['pmp']         = $this->getCampo('pmp');

        $campos['sum_stock_sap']    = $this->getCampo('sum_stock_sap');
        $campos['sum_stock_fisico'] = $this->getCampo('sum_stock_fisico');
        if (request()->input('incl_ajustes')) {
            $campos['sum_stock_ajuste'] = $this->getCampo('sum_stock_ajuste');
        }
        $campos['sum_stock_diff'] = $this->getCampo('sum_stock_diff');

        $campos['sum_valor_sap']    = $this->getCampo('sum_valor_sap');
        $campos['sum_valor_fisico'] = $this->getCampo('sum_valor_fisico');
        if (request()->input('incl_ajustes')) {
            $campos['sum_valor_ajuste'] = $this->getCampo('sum_valor_ajuste');
        }
        $campos['sum_valor_diff'] = $this->getCampo('sum_valor_diff');

        Reporte::setOrderCampos($campos, 'catalogo');

        return $campos;
    }

    public function reporteMaterialFaltante()
    {
        $inclAjuste = request()->input('incl_ajustes') ? ' + d.stock_ajuste' : '';

        $selectFields = [
            'd.catalogo',
            'd.descripcion',
            'd.um',
            'c.pmp',
            \DB::raw('(SUM(stock_sap) - 0.5 * (SUM(stock_sap + stock_fisico'.$inclAjuste.') - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as q_faltante'),
            \DB::raw('(0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as q_coincidente'),
            \DB::raw('(SUM((stock_fisico'.$inclAjuste.')) - 0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as q_sobrante'),
            \DB::raw('c.pmp * (SUM(stock_sap) - 0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as v_faltante'),
            \DB::raw('c.pmp * (0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as v_coincidente'),
            \DB::raw('c.pmp * (SUM((stock_fisico'.$inclAjuste.')) - 0.5 * (SUM(stock_sap + (stock_fisico'.$inclAjuste.')) - ABS(SUM(stock_sap - (stock_fisico'.$inclAjuste.'))))) as v_sobrante'),
        ];

        $groupByFields = ['catalogo', 'descripcion', 'um', 'pmp'];

        return \DB::table(\DB::raw(config('invfija.bd_detalle_inventario').' as d'))
            ->where('id_inventario', '=', $this->id)
            ->leftJoin(\DB::raw(config('invfija.bd_catalogos').' as c'), 'd.catalogo', '=', 'c.catalogo')
            ->select($selectFields)
            ->groupBy($groupByFields)
            ->orderBy('catalogo')
            ->get();
    }

    public function camposReporteMaterialFaltante()
    {
        $campos = [];

        $campos['catalogo']    = $this->getCampo('catalogo');
        $campos['descripcion'] = $this->getCampo('descripcion');
        $campos['um']          = $this->getCampo('um');
        $campos['pmp']         = $this->getCampo('pmp');

        $campos['q_faltante']    = $this->getCampo('q_faltante');
        $campos['q_coincidente'] = $this->getCampo('q_coincidente');
        $campos['q_sobrante']    = $this->getCampo('q_sobrante');
        $campos['v_faltante']    = $this->getCampo('v_faltante');
        $campos['v_coincidente'] = $this->getCampo('v_coincidente');
        $campos['v_sobrante']    = $this->getCampo('v_sobrante');

        Reporte::setOrderCampos($campos, 'catalogo');

        return $campos;
    }
}
