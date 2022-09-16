<?php

use Illuminate\Support\HtmlString;

if (! function_exists('fmtCantidad')) {
    /**
     * Formatea cantidades numéricas con separador decimal y de miles
     *
     * @param  int|float  $valor        Valor a formatear
     * @param  int  $decimales    Cantidad de decimales a mostrar
     * @return string                   Valor formateado
     */
    function fmtCantidad($valor = 0, $decimales = 0): string
    {
        if (! is_numeric($valor)) {
            return '';
        }

        $locale = localeconv();

        // return number_format($valor, $decimales, $locale['decimal_point'], $locale['thousands_sep']);
        return number_format($valor, $decimales, ',', '.');
    }
}

// --------------------------------------------------------------------

if (! function_exists('fmtMonto')) {
    /**
     * Formatea cantidades numéricas como un monto
     *
     * @param  int  $monto        Valor a formatear
     * @return HtmlString
     */
    function fmtMonto($monto = 0): HtmlString
    {
        $locale = localeconv();

        if (! is_numeric($monto)) {
            return new HtmlString('');
        }

        $currencySymbol = empty($locale['currency_symbol']) ? '$' : $locale['currency_symbol'];

        return new HtmlString($currencySymbol.'&nbsp;'.fmtCantidad($monto, 0));
    }
}

// --------------------------------------------------------------------

if (! function_exists('themeColor')) {
    /**
     * Devuelve el color del elemento
     *
     * @param  string  $elemento
     * @return string
     */
    function themeColor(string $elemento): string
    {
        return config('invfija.colors.'.config('invfija.theme').'.'.$elemento) ?: '';
    }
}
