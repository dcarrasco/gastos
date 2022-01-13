<?php

return [
    'app_nombre' => 'AppGastos',

    // Rutas ORM que necesitan correccion para mostrar bien menu selected
    'gastosConfig_create' => 'gastosConfig.index',
    'gastosConfig_show' => 'gastosConfig.index',
    'gastosConfig_edit' => 'gastosConfig.index',
    'gastosConfig_update' => 'gastosConfig.index',

    'aclConfig_create' => 'aclConfig.index',
    'aclConfig_show' => 'aclConfig.index',
    'aclConfig_edit' => 'aclConfig.index',

    'gastos_addGasto' => 'gastos.showMes',
    'gastos_borrarGasto' => 'gastos.showMes',
    'gastos_ingresoMasivoAdd' => 'gastos.ingresoMasivo',
    'gastos_addInversion' => 'gastos.ingresoInversion',

    'theme' => 'indigo',
    // 'theme' => 'default',

    'colors' => [
        'default' => [
            'button_primary' => 'bg-blue-500',
            'button_primary_hover' => 'bg-blue-600',
            'menu_bg' => 'bg-gray-700',
            'tr_hover' => 'bg-blue-100',
            'thead_bg' => 'bg-gray-300',
        ],
        'indigo' => [
            'button_primary' => 'bg-indigo-700',
            'button_primary_hover' => 'bg-indigo-800',
            'menu_bg' => 'bg-indigo-900',
            'tr_hover' => 'bg-indigo-100',
            'thead_bg' => 'bg-gray-300',
        ],
    ],
];
