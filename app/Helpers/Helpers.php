<?php

use Illuminate\Support\Arr;

if (!function_exists('ajax_options')) {
    function ajax_options($opciones): string
    {
        return collect($opciones)
            ->map(function ($item, $key) {
                return ['key' => $key, 'value' => $item];
            })
            ->reduce(function ($carry, $elem) {
                return $carry.'<option value="'.$elem['key'].'">'.e($elem['value']).'</option>';
            }, '');
    }
}

// --------------------------------------------------------------------

if (!function_exists('models_array_options')) {
    function models_array_options($models)
    {
        return $models->mapWithKeys(function ($elem) {
            return [$elem->getKey() => (string) $elem];
        });
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

        return (string) (($mes === 12) ? ($ano+1)*10000+(1)*100+1 : $ano*10000+($mes+1)*100+1);
    }
}

// --------------------------------------------------------------------

if (!function_exists('print_message')) {
    /**
     * Devuelve un mensaje de alerta o error
     *
     * @param  string $mensaje Mensaje a desplegar
     * @param  string $tipo    Tipo de mensaje (warning, danger, info, success)
     * @return string          Mensaje formateado
     */
    function print_message($mensaje = '', $tipo = 'info')
    {
        if ($mensaje or $mensaje !== '') {
            // carga objeto global CI
            $ci =& get_instance();

            $texto_tipo = 'INFORMACI&Oacute;N';
            $img_tipo   = 'info-sign';

            if ($tipo === 'warning') {
                $texto_tipo = 'ALERTA';
                $img_tipo   = 'warning-sign';
            } elseif ($tipo === 'danger' or $tipo === 'error') {
                $tipo = 'danger';
                $texto_tipo = 'ERROR';
                $img_tipo   = 'exclamation-sign';
            } elseif ($tipo === 'success') {
                $texto_tipo = '&Eacute;XITO';
                $img_tipo   = 'ok-sign';
            }

            $arr_datos_view = array(
                'tipo'       => $tipo,
                'texto_tipo' => $texto_tipo,
                'img_tipo'   => $img_tipo,
                'mensaje'    => $mensaje,
            );

            return $ci->parser->parse('common/alert', $arr_datos_view, true);
        }
    }
}

// --------------------------------------------------------------------

if (!function_exists('set_message')) {
    /**
     * Devuelve un mensaje de alerta o error
     *
     * @param  string $mensaje Mensaje a desplegar
     * @param  string $tipo    Tipo de mensaje (warning, danger, info, success)
     * @return void
     */
    function set_message($mensaje = '', $tipo = 'info')
    {
        // carga objeto global CI
        $ci =& get_instance();

        $ci->session->set_flashdata('msg_alerta', print_message($mensaje, $tipo));
    }
}

// --------------------------------------------------------------------

if (!function_exists('print_validation_errors')) {
    /**
     * Imprime errores de validacion
     *
     * @return string Errores de validacion
     */
    function print_validation_errors()
    {
        // carga objeto global CI
        $ci =& get_instance();
        $ci->form_validation->set_error_delimiters('<li> ', '</li>');

        if (validation_errors()) {
            return print_message('<ul>'.validation_errors().'</ul>', 'danger');
        }

        return null;
    }
}

// --------------------------------------------------------------------

if (!function_exists('formArrayFormat')) {
    /**
     * Formatea un arreglo para que sea usado en un formuario select
     * Espera que el arreglo tenga a lo menos las llaves "llave" y "valor"
     *
     * @param  array  $arreglo Arreglo a transformar
     * @param  string $msg_ini Elemento inicial a desplegar en select
     * @return array           Arreglo con formato a utilizar
     */
    function formArrayFormat($arreglo = array(), $msg_ini = '')
    {
        $arr_combo = array();

        if ($msg_ini !== '') {
            $arr_combo[''] = $msg_ini;
        }

        foreach ($arreglo as $item) {
            $arr_combo[$item['llave']] = $item['valor'];
        }

        return $arr_combo;
    }
}


// --------------------------------------------------------------------

if (!function_exists('formError')) {
    /**
     * Indica si el elemento del formulario tiene un error de validación
     *
     * @param  string $formField Nombre del elemento del formulario
     * @return string            Indicador de error del elemento
     */
    function formError($formField = '')
    {
        return $errors->has($formField) ? 'has-error' : '';
    }
}


// --------------------------------------------------------------------

if (!function_exists('fmtCantidad')) {
    /**
     * Formatea cantidades numéricas con separador decimal y de miles
     *
     * @param  integer $valor        Valor a formatear
     * @param  integer $decimales    Cantidad de decimales a mostrar
     * @param  boolean $mostrar_cero Indica si muestra o no valores ceros
     * @param  boolean $format_diff  Indica si formatea valores positivos (verde) y negativos (rojo)
     * @return string                Valor formateado
     */
    function fmtCantidad($valor = 0, $decimales = 0, $mostrar_cero = false, $format_diff = false)
    {
        if (!is_numeric($valor)) {
            return null;
        }

        $cero = $mostrar_cero ? '0' : '';
        $valor_formateado = ($valor === 0) ? $cero : number_format($valor, $decimales, ',', '.');

        $format_start = '';
        $format_end   = '';
        if ($format_diff) {
            $format_start = ($valor > 0)
                ? '<strong><span class="text-success">+'
                : (($valor < 0) ? '<strong><span class="text-danger">' : '');

            $format_end = ($valor === 0) ? '' : '</span></strong>';
        }

        return $format_start.$valor_formateado.$format_end;
    }
}


// --------------------------------------------------------------------

if (!function_exists('fmtMonto')) {
    /**
     * Formatea cantidades numéricas como un monto
     *
     * @param  integer $monto        Valor a formatear
     * @param  string  $unidad       Unidad a desplegar
     * @param  string  $signo_moneda Simbolo monetario
     * @param  integer $decimales    Cantidad de decimales a mostrar
     * @param  boolean $mostrar_cero Indica si muestra o no valores ceros
     * @param  boolean $format_diff  Indica si formatea valores positivos (verde) y negativos (rojo)
     * @return string                Monto formateado
     */
    function fmtMonto(
        $monto = 0,
        $unidad = 'UN',
        $signo_moneda = '$',
        $decimales = 0,
        $mostrar_cero = false,
        $format_diff = false
    ) {
        if (!is_numeric($monto)) {
            return null;
        }

        if ($monto === 0 and ! $mostrar_cero) {
            return '';
        }

        if (strtoupper($unidad) === 'UN') {
            $valor_formateado = $signo_moneda . '&nbsp;' . number_format($monto, $decimales, ',', '.');
        } elseif (strtoupper($unidad) === 'MM') {
            $valor_formateado = 'MM'.$signo_moneda.'&nbsp;'
                .number_format($monto/1000000, ($monto > 10000000) ? 0 : 1, ',', '.');
        }

        $format_start = '';
        $format_end   = '';
        if ($format_diff) {
            $format_start = ($monto > 0)
                ? '<strong><span class="text-success">+'
                : (($monto < 0) ? '<strong><span class="text-danger">' : '');

            $format_end   = ($monto === 0) ? '' : '</span></strong>';
        }

        return $format_start.$valor_formateado.$format_end;
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

        $hora = (int) ($segundos_totales/3600);
        $hora = (strlen($hora) === 1) ? '0' . $hora : $hora;

        $minutos = (int) (($segundos_totales - ((int) $hora) *3600)/60);
        $minutos = (strlen($minutos) === 1) ? '0' . $minutos : $minutos;

        $segundos = (int) ($segundos_totales - ($hora*3600 + $minutos*60));
        $segundos = (strlen($segundos) === 1) ? '0' . $segundos : $segundos;

        return $hora.$separador.$minutos.$separador.$segundos;
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

        return fmtCantidad($numero_rut).'-'.strtoupper($dv_rut);
    }
}

// --------------------------------------------------------------------

if (!function_exists('getArrDiasMes')) {
    /**
     * Devuelve arreglo con dias del mes
     *
     * @param  string $anomes Mes y año a consultar (formato YYYYMM)
     * @return array          Arreglo con dias del mes (llaves en formato DD)
     */
    function getArrDiasMes($anomes = null)
    {
        $mes = (int) substr($anomes, 4, 2);
        $ano = (int) substr($anomes, 0, 4);

        return collect(array_fill(1, daysInMonth($mes, $ano), null))
            ->mapWithKeys(function ($valor, $indice) {
                return [str_pad($indice, 2, '0', STR_PAD_LEFT) => $valor];
            })
            ->all();
    }
}

// --------------------------------------------------------------------

if (!function_exists('daysInMonth')) {
    /**
     * Number of days in a month
     *
     * Takes a month/year as input and returns the number of days
     * for the given month/year. Takes leap years into consideration.
     *
     * @param   int a numeric month
     * @param   int a numeric year
     * @return  int
     */
    function daysInMonth($month = 0, $year = '')
    {
        if ($month < 1 or $month > 12) {
            return 0;
        } elseif (!is_numeric($year) or strlen($year) !== 4) {
            $year = date('Y');
        }

        if ($month == 2 && ($year % 400 === 0 or ($year % 4 === 0 && $year % 100 !== 0))) {
            return 29;
        }

        $daysInMonth = [31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31];

        return $daysInMonth[$month - 1];
    }
}

// --------------------------------------------------------------------

if (!function_exists('cached_query')) {
    /**
     * Ejecuta una función de un modelo (query) o devuelve el resultado
     * almacenado en el cache.
     *
     * @param  string $cache_id ID o identificador unico de la función y sus parámetros
     * @param  mixed  $object   Objeto o modelo que contiene la función a ejecutar
     * @param  string $method   Nombre de la función o método a ejecutar
     * @param  array  $params   Arreglo con los parámetros de la función a ejecutar
     * @return mixed            Resultado de la función
     */
    function cached_query($cache_id = '', $object = null, $method = '', $params = array())
    {
        $ci =& get_instance();
        $ci->load->driver('cache', array('adapter' => 'file'));
        $cache_ttl = 300;

        $params = (! is_array($params)) ? array($params) : $params;

        log_message(
            'debug',
            "cached_query: id({$cache_id}), object(".get_class($object)
            ."), method({$method}), params(".json_encode($params).")"
        );

        // limpia caches antiguos
        if (is_array($ci->cache->cache_info())) {
            foreach ($ci->cache->cache_info() as $cache_ant_id => $cache_ant_data) {
                if ($cache_ant_data['date'] < now() - $cache_ttl and
                    strtolower(substr($cache_ant_data['name'], -4)) !== 'html'
                ) {
                    $ci->cache->delete($cache_ant_id);
                }
            }
        }

        if (!method_exists($object, $method)) {
            log_message('error', 'cached_query: Metodo "'.$method.'"" no existe en objeto "'.get_class($object).'".');
            return null;
        }

        $cache_id = hash('md5', $cache_id);

        if (!$result = $ci->cache->get($cache_id)) {
            $result = call_user_func_array(array($object, $method), $params);
            $ci->cache->save($cache_id, $result, $cache_ttl);
        }

        return $result;
    }
}

// --------------------------------------------------------------------

if (!function_exists('clase_cumplimiento_consumos')) {
    /**
     * Devuelve la clase pintar el cumplimiento diario
     *
     * @param  integer $porcentaje_cumplimiento % de cumplimiento
     * @return string                           Clase
     */
    function clase_cumplimiento_consumos($porcentaje_cumplimiento = 0)
    {
        return $porcentaje_cumplimiento >= 0.9
            ? 'success'
            : ($porcentaje_cumplimiento >= 0.6
                ? 'warning'
                : 'danger');
    }
}
