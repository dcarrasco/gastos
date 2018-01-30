<?php

namespace App\Toa;

use App\Stock\ClaseMovimiento;
use App\Helpers\Reporte;

class Peticiones
{
    const CENTROS_CONSUMO = ['CH32', 'CH33'];

    protected static $camposReporte = [
        'ciudades' => 'id_ciudad',
        'empresas' => 'vale_acomp',
        'tecnicos' => 'cliente',
        'tiposMaterial' => 'id_tip_material_trabajo',
        'materiales' => 'material',
        'lotes' => 'lote',
        'lotesMateriales' => ['lote', 'material'],
        'pep' => ['codigo_movimiento', 'elemento_pep'],
        'tiposTrabajo' => 'carta_porte',
    ];

    public static function getReporte($data)
    {
        $reporte = new Reporte($data, static::getCamposReporte());

        return $reporte->make();
    }

    public static function getPeticiones($nombreReporte, $fechaDesde, $fechaHasta, $id, $id2 = null)
    {
        $select = [
            \DB::raw('min(m.fecha_contabilizacion) as fecha'),
            'm.referencia',
            'm.carta_porte',
            'c.empresa',
            'm.cliente',
            'b.tecnico',
            'd.acoord_x',
            'd.acoord_y',
            \DB::raw("'ver detalle' as texto_link"),
            \DB::raw('sum(-m.cantidad_en_um) as cant'),
            \DB::raw('sum(-m.importe_ml) as monto'),
        ];

        $groupBy = [
            'm.referencia',
            'm.carta_porte',
            'c.empresa',
            'm.cliente',
            'b.tecnico',
            'd.acoord_x',
            'd.acoord_y',
        ];

        $queryReporte = \DB::table(\DB::raw(config('invfija.bd_movimientos_sap_fija').' m'))
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'm.cliente', '=', 'b.id_tecnico')
            ->leftJoin(\DB::raw(config('invfija.bd_empresas_toa').' c'), 'm.vale_acomp', '=', 'c.id_empresa')
            ->leftJoin(\DB::raw(config('invfija.bd_peticiones_toa').' d'), function ($join) {
                $join->on('m.referencia', '=', 'd.appt_number');
                $join->on('d.astatus', '=', \DB::raw("'complete'"));
            })
            ->leftJoin(\DB::raw(config('invfija.bd_catalogo_tip_material_toa').' e'), 'm.material', '=', 'e.id_catalogo')
            ->leftJoin(\DB::raw(config('invfija.bd_tip_material_trabajo_toa').' f'), 'e.id_tip_material_trabajo', '=', 'f.id')
            ->where('m.fecha_contabilizacion', '>=', $fechaDesde)
            ->where('m.fecha_contabilizacion', '<=', $fechaHasta)
            ->whereIn('m.codigo_movimiento', ClaseMovimiento::transaccionesConsumoToa())
            ->whereIn('m.centro', static::CENTROS_CONSUMO);

        return static::filtroReporte($queryReporte, $nombreReporte, $id, $id2)
            ->select($select)
            ->groupBy($groupBy)
            ->orderBy('m.referencia')
            ->get();
    }

    protected static function filtroReporte($query, $nombreReporte, $id, $id2)
    {
        $arrId = [$id, $id2];

        $camposFiltro = array_get(static::$camposReporte, $nombreReporte);
        $camposFiltro = is_array($camposFiltro) ? $camposFiltro : [$camposFiltro];

        foreach ($camposFiltro as $campo)
        {
            $idCampo = array_shift($arrId);
            $query = $query->where($campo, $idCampo);
        }

        return $query;
    }

    protected static function getCamposReporte()
    {
        $camposReporte = [
            'referencia' => ['titulo' => 'Numero peticion', 'tipo' => 'link', 'route' => 'toa.peticion'],
            'fecha' => ['titulo' => 'Fecha', 'tipo' => 'fecha'],
            'carta_porte' => ['titulo' => 'Tipo trabajo', 'tipo' => 'texto'],
            'empresa' => ['titulo' => 'Empresa', 'tipo' => 'texto'],
            'cliente' => ['titulo' => 'Cod Tecnico', 'tipo' => 'texto'],
            'tecnico' => ['titulo' => 'Nombre Tecnico', 'tipo' => 'texto'],
            'cant' => ['titulo' => 'Cantidad', 'tipo' => 'numero', 'class' => 'text-right'],
            'monto' => ['titulo' => 'Monto', 'tipo' => 'valor', 'class' => 'text-right'],
        ];

        Reporte::setOrderCampos($camposReporte, 'referencia');

        return $camposReporte;
    }

    public static function peticion($idPeticion)
    {
        if (empty($idPeticion)) {
            return null;
        }

        $peticionToa = (array) \DB::table(\DB::raw(config('invfija.bd_peticiones_toa').' d'))
            ->leftJoin(\DB::raw(config('invfija.bd_empresas_toa').' c'), 'd.contractor_company', '=', 'c.id_empresa')
            ->where('appt_number', $idPeticion)
            ->where('astatus', 'complete')
            ->first();

        $materialesSap = \DB::table(\DB::raw(config('invfija.bd_movimientos_sap_fija').' a'))
            ->leftJoin(\DB::raw(config('invfija.bd_tecnicos_toa').' b'), 'a.cliente', '=', 'b.id_tecnico')
            ->leftJoin(\DB::raw(config('invfija.bd_empresas_toa').' c'), 'a.vale_acomp', '=', 'c.id_empresa')
            ->whereIn('codigo_movimiento', ClaseMovimiento::transaccionesConsumoToa())
            ->whereIn('centro', static::CENTROS_CONSUMO)
            ->where('referencia', $idPeticion)
            ->select([
                \DB::raw('a.fecha_contabilizacion as fecha'),
                'a.referencia', 'c.empresa', 'a.cliente', 'b.tecnico', 'a.codigo_movimiento',
                'a.texto_movimiento', 'a.elemento_pep', 'a.documento_material', 'a.centro',
                'a.almacen', 'a.material', 'a.texto_material', 'a.serie_toa', 'a.lote',
                'a.valor', 'a.umb', \DB::raw('(-a.cantidad_en_um) as cant'),
                \DB::raw('(-a.importe_ml) as monto'), 'a.usuario', 'a.vale_acomp',
                'a.carta_porte', 'a.usuario',
            ])
            ->orderBy('a.material')
            ->orderBy('a.codigo_movimiento')
            ->get();

        $materialesToa = \DB::table(config('invfija.bd_materiales_peticiones_toa'))
            ->where('aid', $peticionToa['aid'])
            ->orderBy('XI_SAP_CODE')
            ->get();

        $materialesVpi = \DB::table(config('invfija.bd_peticiones_vpi'))
            ->where('appt_number', $peticionToa['appt_number'])
            ->orderBy('ps_id')
            ->get();

        return compact('peticionToa', 'materialesSap', 'materialesToa', 'materialesVpi');
    }
}
