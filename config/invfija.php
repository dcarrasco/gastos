<?php

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

    'llavesApp' => [
        'inventario' => [
            'showHoja'     => 'b386b510e56f73e',
            'addLinea'     => 'b386b510e56f73e',
            'reporte'      => '2dfc992232fe108',
            'ajustes'      => 'fda0416c87cceb5',
            'upload'       => 'fda0416c87cceb5',
            'imprimirForm' => 'fda0416c87cceb5',
        ],
        'inventarioConfig'  => [
            'index'  => '81b87511e28532f',
            'edit'   => '81b87511e28532f',
            'create' => '81b87511e28532f',
        ],
        'stock' => [
            'analisisSeries'     => '02173df489952b0',
            'consultaStockMovil' => 'a37f5a1e01ed158',
            'consultaStockFija'  => 'a37f5a1e01ed158',
        ],
        'stockConfig' => [
            'index'  => '46f163ae6eddc0c',
            'edit'   => '46f163ae6eddc0c',
            'create' => '46f163ae6eddc0c',
        ],
        'toa' => [
            'peticion'   => '470d090393a1e7f',
            'controles'  => 'cd3b54ac404725c',
            'consumos'   => '0bbf9db94624559',
            'asignacion' => 'd5db321c52cc9aa',
        ],
        'toaConfig' => [
            'index'  => '80aa1468e0a10ca',
            'edit'   => '80aa1468e0a10ca',
            'create' => '80aa1468e0a10ca',
        ],
        'aclConfig' => [
            'index'  => '4bd0769215f77e7',
            'edit'   => '4bd0769215f77e7',
            'create' => '4bd0769215f77e7',
        ],
        'adminbd' => [
            'queries'  => 'cb3f6b85ca73e82',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Tablas del sistema
    |--------------------------------------------------------------------------
    |
    | Listado con las tablas del sistema
    |
    */

    // Inventarios
    'bd_usuarios'           => env('BD_INVENTARIO', '').'fija_usuarios',
    'bd_inventarios'        => env('BD_INVENTARIO', '').'fija_inventarios',
    'bd_tipos_inventario'   => env('BD_INVENTARIO', '').'fija_tipos_inventario',
    'bd_detalle_inventario' => env('BD_INVENTARIO', '').'fija_detalle_inventario',
    'bd_catalogos'          => env('BD_INVENTARIO', '').'fija_catalogos',
    'bd_catalogos_fotos'    => env('BD_INVENTARIO', '').'fija_catalogos_fotos',
    'bd_centros'            => env('BD_INVENTARIO', '').'fija_centros',
    'bd_unidades'           => env('BD_INVENTARIO', '').'fija_unidades',
    'bd_auditores'          => env('BD_INVENTARIO', '').'fija_auditores',
    'bd_almacenes'          => env('BD_INVENTARIO', '').'fija_almacenes',
    'bd_familias'           => env('BD_INVENTARIO', '').'fija_familias',
    'bd_tipo_ubicacion'     => env('BD_INVENTARIO', '').'fija_tipo_ubicacion',
    'bd_ubic_tipoubic'      => env('BD_INVENTARIO', '').'fija_ubicacion_tipo_ubicacion',

    // ACL
    'bd_app'         => env('BD_INVENTARIO', '').'acl_app',
    'bd_modulos'     => env('BD_INVENTARIO', '').'acl_modulo',
    'bd_rol'         => env('BD_INVENTARIO', '').'acl_rol',
    'bd_usuario_rol' => env('BD_INVENTARIO', '').'acl_usuario_rol',
    'bd_rol_modulo'  => env('BD_INVENTARIO', '').'acl_rol_modulo',
    'bd_captcha'     => env('BD_INVENTARIO', '').'ci_captcha',
    'bd_pcookies'    => env('BD_INVENTARIO', '').'fija_pcookies',

    // Stock
    'bd_almacenes_sap'        => env('BD_LOGISTICA', '').'cp_almacenes2',
    'bd_tipoalmacen_sap'      => env('BD_LOGISTICA', '').'cp_tipos_almacenes',
    'bd_tiposalm_sap'         => env('BD_LOGISTICA', '').'cp_tiposalm',
    'bd_clasifalm_sap'        => env('BD_LOGISTICA', '').'cp_clasifalm',
    'bd_clasif_tipoalm_sap'   => env('BD_LOGISTICA', '').'cp_clasif_tipoalm',
    'bd_tipo_clasifalm_sap'   => env('BD_LOGISTICA', '').'cp_tipo_clasifalm',
    'bd_reporte_clasif'       => env('BD_LOGISTICA', '').'cp_reporte_clasificacion',
    'bd_proveedores'          => env('BD_LOGISTICA', '').'cp_proveedores',
    'bd_permanencia'          => env('BD_LOGISTICA', '').'cp_permanencia',
    'bd_permanencia_fija'     => env('BD_LOGISTICA', '').'perm_series_consumo_fija',
    'bd_stock_movil'          => env('BD_LOGISTICA', '').'stock_scl',
    'bd_stock_movil_res01'    => env('BD_LOGISTICA', '').'stock_scl_res01',
    'bd_stock_movil_fechas'   => env('BD_LOGISTICA', '').'stock_scl_fechas',
    'bd_stock_fija'           => env('BD_LOGISTICA', '').'bd_stock_sap_fija',
    'bd_stock_fija_fechas'    => env('BD_LOGISTICA', '').'bd_stock_sap_fija_fechas',
    'bd_movimientos_sap'      => env('BD_LOGISTICA', '').'mov_hist',
    'bd_movimientos_sap_fija' => env('BD_LOGISTICA', '').'bd_movmb51_fija',
    'bd_resmovimientos_sap'   => env('BD_LOGISTICA', '').'mov_hist_res01',
    'bd_cmv_sap'              => env('BD_LOGISTICA', '').'cp_cmv',
    'bd_fechas_sap'           => env('BD_LOGISTICA', '').'cp_fechas',
    'bd_usuarios_sap'         => env('BD_LOGISTICA', '').'cp_usuarios',
    'bd_despachos_sap'        => env('BD_LOGISTICA', '').'despachos_sap',
    'bd_materiales_sap'       => env('BD_LOGISTICA', '').'al_articulos',
    'bd_materiales2_sap'      => env('BD_LOGISTICA', '').'cp_materiales_sap',
    'bd_stock_seriado_sap'    => env('BD_LOGISTICA', '').'bd_stock_sap',
    'bd_stock_seriado_sap_03' => env('BD_LOGISTICA', '').'bd_stock_03',
    'bd_stock_scl'            => env('BD_LOGISTICA', '').'bd_stock_scl',
    'bd_al_bodegas'           => env('BD_LOGISTICA', '').'al_bodegas',
    'bd_al_tipos_bodegas'     => env('BD_LOGISTICA', '').'al_tipos_bodegas',
    'bd_al_tipos_stock'       => env('BD_LOGISTICA', '').'al_tipos_stock',
    'bd_al_estados'           => env('BD_LOGISTICA', '').'al_estados',
    'bd_al_usos'              => env('BD_LOGISTICA', '').'al_usos',
    'bd_trafico_mes'          => env('BD_LOGISTICA', '').'trafico_mes',

    'bd_trafico_abocelamist'  => env('BD_CONTROLES', '').'trafico_abocelamist',
    'bd_trafico_clientes'     => env('BD_CONTROLES', '').'trafico_clientes',
    'bd_trafico_causabaja'    => env('BD_CONTROLES', '').'trafico_causabaja',
    'bd_trafico_dias_proc'    => env('BD_CONTROLES', '').'trafico_dias_procesados',
    'bd_pmp'                  => env('BD_PLANIFICACION', '').'ca_stock_sap_04',

    // Despachos
    'bd_despachos_pack'       => env('BD_LOGISTICA', '').'despachos_sap_res01_pack',

    // TOA
    'bd_peticiones_sap'            => env('BD_TOA', '').'toa_peticiones_sap',
    'bd_peticiones_toa'            => env('BD_TOA', '').'appt_toa2',
    'bd_materiales_peticiones_toa' => env('BD_TOA', '').'inv_fields_toa',
    'bd_peticiones_vpi'            => env('BD_TOA', '').'consumo_toa_vpi',
    'bd_tecnicos_toa'              => env('BD_TOA', '').'toa_tecnicos',
    'bd_empresas_toa'              => env('BD_TOA', '').'toa_empresas',
    'bd_ciudades_toa'              => env('BD_TOA', '').'toa_ciudades',
    'bd_empresas_toa_tiposalm'     => env('BD_TOA', '').'toa_empresas_tiposalm',
    'bd_empresas_ciudades_toa'     => env('BD_TOA', '').'toa_empresas_ciudades',
    'bd_empresas_ciudades_almacenes_toa' => env('BD_TOA', '').'toa_empresas_ciudades_almacenes',
    'bd_tipos_trabajo_toa'         => env('BD_TOA', '').'toa_tipos_trabajo',
    'bd_tip_material_trabajo_toa'  => env('BD_TOA', '').'toa_tip_material_trabajo',
    'bd_catalogo_tip_material_toa' => env('BD_TOA', '').'toa_catalogo_tip_material',
    'bd_resumen_panel_toa'         => env('BD_TOA', '').'toa_resumen_panel',

];
