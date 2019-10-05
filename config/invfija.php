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
    'app_nombre' => 'AppGastos',

    'llavesApp' => [
        'aclConfig' => [
            'index'  => '4bd0769215f77e7',
            'edit'   => '4bd0769215f77e7',
            'create' => '4bd0769215f77e7',
            'show' => '4bd0769215f77e7',
        ],
        'gastosConfig' => [
            'index'  => 'sv8346jhsguan2o',
            'edit'   => 'sv8346jhsguan2o',
            'create' => 'sv8346jhsguan2o',
            'show' => 'sv8346jhsguan2o',
        ],
        'gastos' => [
            'showMes' => 'insh85tons930ic',
            'reporte' => 'pd7nd92jtopol0q',
            'ingresoMasivo' => 'oms73ueg39dmn03',
            'ingresoInversion' => '837dhpo93hnbq3v',
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

    // ACL
    'bd_usuarios'    => env('BD_INVENTARIO', '').'acl_usuarios',
    'bd_app'         => env('BD_INVENTARIO', '').'acl_app',
    'bd_modulos'     => env('BD_INVENTARIO', '').'acl_modulo',
    'bd_rol'         => env('BD_INVENTARIO', '').'acl_rol',
    'bd_usuario_rol' => env('BD_INVENTARIO', '').'acl_usuario_rol',
    'bd_rol_modulo'  => env('BD_INVENTARIO', '').'acl_rol_modulo',
    'bd_captcha'     => env('BD_INVENTARIO', '').'ci_captcha',
    'bd_pcookies'    => env('BD_INVENTARIO', '').'fija_pcookies',


];
