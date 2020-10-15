<?php

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

// setlocale(LC_ALL, '');

if (!function_exists('arrayToInstanceCollection')) {
    function arrayToInstanceCollection(array $classArray): Collection
    {
        return collect($classArray)
            ->map(function ($class) {
                return new $class;
            });
    }
}

// --------------------------------------------------------------------

if (!function_exists('ajax_options')) {
    function ajax_options($opciones): string
    {
        return collect($opciones)
            ->map(function ($item, $key) {
                return ['key' => $key, 'value' => $item];
            })
            ->reduce(function ($carry, $elem) {
                return "{$carry}<option value=\"{$elem['key']}\">" . e($elem['value']) . '</option>';
            }, '');
    }
}

// --------------------------------------------------------------------

if (!function_exists('diaSemana')) {
    function diaSemana($numDiaSem)
    {
        $dias = ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sa'];

        return Arr::get($dias, $numDiaSem);
    }
}

// --------------------------------------------------------------------

if (!function_exists('getFechaHasta')) {
    /**
     * Devuelve la fecha más un mes
     *
     * @param  string $anomes Mes y año a consultar (formato YYYYMM)
     * @return string         Fecha más un mes (formato YYYYMMDD)
     */
    function getFechaHasta($anomes = null)
    {
        $mes = (int) substr($anomes, 4, 2);
        $ano = (int) substr($anomes, 0, 4);

        return (string) (($mes === 12) ? ($ano + 1) * 10000 + (1) * 100 + 1 : $ano * 10000 + ($mes + 1) * 100 + 1);
    }
}

// --------------------------------------------------------------------

if (!function_exists('fmtCantidad')) {
    /**
     * Formatea cantidades numéricas con separador decimal y de miles
     *
     * @param  integer $valor        Valor a formatear
     * @param  integer $decimales    Cantidad de decimales a mostrar
     * @return string                Valor formateado
     */
    function fmtCantidad($valor = 0, $decimales = null): string
    {
        if (!is_numeric($valor)) {
            return '';
        }

        $locale = localeconv();
        $decimales = $decimales ?? $locale['frac_digits'];

        // return number_format($valor, $decimales, $locale['decimal_point'], $locale['thousands_sep']);
        return number_format($valor, 0, ',', '.');
    }
}


// --------------------------------------------------------------------

if (!function_exists('fmtMonto')) {
    /**
     * Formatea cantidades numéricas como un monto
     *
     * @param  integer $monto        Valor a formatear
     * @return HtmlString
     */
    function fmtMonto($monto = 0): HtmlString
    {
        $locale = localeconv();

        if (!is_numeric($monto)) {
            return new HtmlString('');
        }

        $currencySymbol = empty($locale['currency_symbol']) ? '$' : $locale['currency_symbol'];

        return new HtmlString($currencySymbol.'&nbsp;'.
            // number_format($monto, $locale['frac_digits'], $locale['decimal_point'], $locale['thousands_sep']));
            number_format($monto, 0, ',', '.'));
    }
}

// --------------------------------------------------------------------

if (!function_exists('fmtPorcentaje')) {

    function fmtPorcentaje($valor = 0, $decimales = null): string
    {
        $locale = localeconv();

        if (!is_numeric($valor)) {
            return '';
        }

        $decimales = $decimales ?? 2;
        // return number_format($valor, $decimales, $locale['decimal_point'], $locale['thousands_sep']).'%';
        return number_format($valor, $decimales, ',', '.').'%';
    }

}
// --------------------------------------------------------------------

if (!function_exists('fmtHora')) {
    /**
     * Devuelve una cantidad de segundos como una hora
     *
     * @param  integer $segundos_totales Cantidad de segundos a formatear
     * @return string       Segundos formateados como hora
     */
    function fmtHora($segundos_totales = 0)
    {
        $separador = ':';

        $hora = (int) ($segundos_totales / 3600);
        $hora = (strlen($hora) === 1) ? '0' . $hora : $hora;

        $minutos = (int) (($segundos_totales - ((int) $hora) * 3600) / 60);
        $minutos = (strlen($minutos) === 1) ? '0' . $minutos : $minutos;

        $segundos = (int) ($segundos_totales - ($hora * 3600 + $minutos * 60));
        $segundos = (strlen($segundos) === 1) ? '0' . $segundos : $segundos;

        return $hora . $separador . $minutos . $separador . $segundos;
    }
}

// --------------------------------------------------------------------

if (!function_exists('fmtFecha')) {
    /**
     * Devuelve una fecha de la BD formateada para desplegar
     *
     * @param  string $fecha   Fecha a formatear
     * @param  string $formato Formato a devolver
     * @return string          Fecha formateada segun formato
     */
    function fmtFecha($fecha = null, $formato = 'Y-m-d')
    {
        $fecha = \Carbon\Carbon::parse($fecha);

        return $fecha->format($formato);
    }
}

// --------------------------------------------------------------------

if (!function_exists('fmtFechaDb')) {
    /**
     * Devuelve una fecha del usuario para consultar en la BD
     *
     * @param  string $fecha Fecha a formatear
     * @return string        Fecha formateada segun formato
     */
    function fmtFechaDb($fecha = null)
    {
        return fmtFecha($fecha, 'Ymd');
    }
}

// --------------------------------------------------------------------

if (!function_exists('fmtRut')) {
    /**
     * Formatea un RUT
     *
     * @param  string $rut RUT a formatear
     * @return string      RUT formateado segun formato
     */
    function fmtRut($numero_rut = null)
    {
        if (!$numero_rut) {
            return null;
        }

        if (strpos($numero_rut, '-') === false) {
            $dv_rut = substr($numero_rut, strlen($numero_rut) - 1, 1);
            $numero_rut = substr($numero_rut, 0, strlen($numero_rut) - 1);
        } else {
            list($numero_rut, $dv_rut) = explode('-', $numero_rut);
        }

        return fmtCantidad($numero_rut) . '-' . strtoupper($dv_rut);
    }
}
