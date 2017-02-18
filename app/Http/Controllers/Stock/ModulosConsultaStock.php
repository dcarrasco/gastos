<?php

namespace App\Http\Controllers\Stock;

trait ModulosConsultaStock
{
    protected $routeName = 'stock.consultaStock';

    protected $menuModulo = [];

    public function __construct()
    {
        $this->menuModulo = [
            'consulta-stock-movil' => [
                'nombre' => trans('stock.sap_menu_movil'),
                'url'    => route('stock.consultaStockMovil'),
                'icono'  => 'mobile'
            ],
            'consulta-stock-fija' => [
                'nombre' => trans('stock.sap_menu_fijo'),
                'url'    => route('stock.consultaStockFija'),
                'icono'  => 'phone'
            ],
            'consulta-stock-transito' => [
                'nombre' => trans('stock.sap_menu_transito'),
                'url'    => route('stock.consultaStockMovil'),
                'icono'  => 'send'
            ],
            'consulta-stock-clasif' => [
                'nombre' => trans('stock.sap_menu_clasif'),
                'url'    => route('stock.consultaStockMovil'),
                'icono'  => 'th'
            ],
        ];

        view()->share('menuModulo', $this->menuModulo);
    }
}
