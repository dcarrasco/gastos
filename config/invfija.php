<?php
// Nombres de las bases de datos
define('BD_INVENTARIO', 'bd_inventario.dbo.');
define('BD_TOA', 'bd_toa.dbo.');
define('BD_LOGISTICA', 'bd_logistica.dbo.');
define('BD_CONTROLES', 'bd_controles.dbo.');
define('BD_PLANIFICACION', 'bd_planificacion.dbo.');

return [
    /*
    |--------------------------------------------------------------------------
    | Tablas del sistema
    |--------------------------------------------------------------------------
    |
    | Listado con las tablas del sistema
    |
    */
    'app_nombre' => 'Inventario Fija',

    /*
    |--------------------------------------------------------------------------
    | Tablas del sistema
    |--------------------------------------------------------------------------
    |
    | Listado con las tablas del sistema
    |
    */

    // Inventarios
    'bd_usuarios'           => BD_INVENTARIO.'fija_usuarios',
    'bd_inventarios'        => BD_INVENTARIO.'fija_inventarios',
    'bd_tipos_inventario'   => BD_INVENTARIO.'fija_tipos_inventario',
    'bd_detalle_inventario' => BD_INVENTARIO.'fija_detalle_inventario',
    'bd_catalogos'          => BD_INVENTARIO.'fija_catalogos',
    'bd_catalogos_fotos'    => BD_INVENTARIO.'fija_catalogos_fotos',
    'bd_centros'            => BD_INVENTARIO.'fija_centros',
    'bd_unidades'           => BD_INVENTARIO.'fija_unidades',
    'bd_auditores'          => BD_INVENTARIO.'fija_auditores',
    'bd_almacenes'          => BD_INVENTARIO.'fija_almacenes',
    'bd_familias'           => BD_INVENTARIO.'fija_familias',
    'bd_tipo_ubicacion'     => BD_INVENTARIO.'fija_tipo_ubicacion',
    'bd_ubic_tipoubic'      => BD_INVENTARIO.'fija_ubicacion_tipo_ubicacion',

    // ACL
    'bd_app'         => BD_INVENTARIO.'acl_app',
    'bd_modulos'     => BD_INVENTARIO.'acl_modulo',
    'bd_rol'         => BD_INVENTARIO.'acl_rol',
    'bd_usuario_rol' => BD_INVENTARIO.'acl_usuario_rol',
    'bd_rol_modulo'  => BD_INVENTARIO.'acl_rol_modulo',
    'bd_captcha'     => BD_INVENTARIO.'ci_captcha',
    'bd_pcookies'    => BD_INVENTARIO.'fija_pcookies',

    // Stock
    'bd_almacenes_sap'        => BD_LOGISTICA.'cp_almacenes',
    'bd_tipoalmacen_sap'      => BD_LOGISTICA.'cp_tipos_almacenes',
    'bd_tiposalm_sap'         => BD_LOGISTICA.'cp_tiposalm',
    'bd_clasifalm_sap'        => BD_LOGISTICA.'cp_clasifalm',
    'bd_clasif_tipoalm_sap'   => BD_LOGISTICA.'cp_clasif_tipoalm',
    'bd_tipo_clasifalm_sap'   => BD_LOGISTICA.'cp_tipo_clasifalm',
    'bd_reporte_clasif'       => BD_LOGISTICA.'cp_reporte_clasificacion',
    'bd_proveedores'          => BD_LOGISTICA.'cp_proveedores',
    'bd_permanencia'          => BD_LOGISTICA.'cp_permanencia',
    'bd_permanencia_fija'     => BD_LOGISTICA.'perm_series_consumo_fija',
    'bd_stock_movil'          => BD_LOGISTICA.'stock_scl',
    'bd_stock_movil_res01'    => BD_LOGISTICA.'stock_scl_res01',
    'bd_stock_movil_fechas'   => BD_LOGISTICA.'stock_scl_fechas',
    'bd_stock_fija'           => BD_LOGISTICA.'bd_stock_sap_fija',
    'bd_stock_fija_fechas'    => BD_LOGISTICA.'bd_stock_sap_fija_fechas',
    'bd_movimientos_sap'      => BD_LOGISTICA.'mov_hist',
    'bd_movimientos_sap_fija' => BD_LOGISTICA.'bd_movmb51_fija',
    'bd_resmovimientos_sap'   => BD_LOGISTICA.'mov_hist_res01',
    'bd_cmv_sap'              => BD_LOGISTICA.'cp_cmv',
    'bd_fechas_sap'           => BD_LOGISTICA.'cp_fechas',
    'bd_usuarios_sap'         => BD_LOGISTICA.'cp_usuarios',
    'bd_despachos_sap'        => BD_LOGISTICA.'despachos_sap',
    'bd_materiales_sap'       => BD_LOGISTICA.'al_articulos',
    'bd_materiales2_sap'      => BD_LOGISTICA.'cp_materiales_sap',
    'bd_stock_seriado_sap'    => BD_LOGISTICA.'bd_stock_sap',
    'bd_stock_seriado_sap_03' => BD_LOGISTICA.'bd_stock_03',
    'bd_stock_scl'            => BD_LOGISTICA.'bd_stock_scl',
    'bd_al_bodegas'           => BD_LOGISTICA.'al_bodegas',
    'bd_al_tipos_bodegas'     => BD_LOGISTICA.'al_tipos_bodegas',
    'bd_al_tipos_stock'       => BD_LOGISTICA.'al_tipos_stock',
    'bd_al_estados'           => BD_LOGISTICA.'al_estados',
    'bd_al_usos'              => BD_LOGISTICA.'al_usos',
    'bd_trafico_mes'          => BD_LOGISTICA.'trafico_mes',
    'bd_trafico_abocelamist'  => BD_CONTROLES.'trafico_abocelamist',
    'bd_trafico_clientes'     => BD_CONTROLES.'trafico_clientes',
    'bd_trafico_causabaja'    => BD_CONTROLES.'trafico_causabaja',
    'bd_trafico_dias_proc'    => BD_CONTROLES.'trafico_dias_procesados',
    'bd_pmp'                  => BD_PLANIFICACION.'ca_stock_sap_04',

    // Despachos
    'bd_despachos_pack'       => BD_LOGISTICA.'despachos_sap_res01_pack',

    // TOA
    'bd_peticiones_sap'            => BD_TOA.'toa_peticiones_sap',
    'bd_peticiones_toa'            => BD_TOA.'appt_toa2',
    'bd_materiales_peticiones_toa' => BD_TOA.'inv_fields_toa',
    'bd_peticiones_vpi'            => BD_TOA.'consumo_toa_vpi',
    'bd_tecnicos_toa'              => BD_TOA.'toa_tecnicos',
    'bd_empresas_toa'              => BD_TOA.'toa_empresas',
    'bd_ciudades_toa'              => BD_TOA.'toa_ciudades',
    'bd_empresas_toa_tiposalm'     => BD_TOA.'toa_empresas_tiposalm',
    'bd_empresas_ciudades_toa'     => BD_TOA.'toa_empresas_ciudades',
    'bd_empresas_ciudades_almacenes_toa' => BD_TOA.'toa_empresas_ciudades_almacenes',
    'bd_tipos_trabajo_toa'         => BD_TOA.'toa_tipos_trabajo',
    'bd_tip_material_trabajo_toa'  => BD_TOA.'toa_tip_material_trabajo',
    'bd_catalogo_tip_material_toa' => BD_TOA.'toa_catalogo_tip_material',
    'bd_resumen_panel_toa'         => BD_TOA.'toa_resumen_panel',

];
