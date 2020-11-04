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

    /*
    |--------------------------------------------------------------------------
    | Tablas del sistema
    |--------------------------------------------------------------------------
    |
    | Listado con las tablas del sistema
    |
    */

    // ACL
    'bd_usuarios'    => env('BD_INVENTARIO', '') . 'acl_usuarios',
    'bd_app'         => env('BD_INVENTARIO', '') . 'acl_app',
    'bd_modulos'     => env('BD_INVENTARIO', '') . 'acl_modulo',
    'bd_rol'         => env('BD_INVENTARIO', '') . 'acl_rol',
    'bd_usuario_rol' => env('BD_INVENTARIO', '') . 'acl_usuario_rol',
    'bd_rol_modulo'  => env('BD_INVENTARIO', '') . 'acl_rol_modulo',
    'bd_captcha'     => env('BD_INVENTARIO', '') . 'ci_captcha',
    'bd_pcookies'    => env('BD_INVENTARIO', '') . 'fija_pcookies',
];
