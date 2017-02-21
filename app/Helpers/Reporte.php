<?php

namespace App\Helpers;

use Collective\Html;

class Reporte
{
    public $campos = [];

    public $datos = [];

    public $template = [
        'table_open'  => '<table class="table table-striped table-hover table-condensed reporte table-fixed-header">',
        'table_close' => '</table>',
        'thead_open'  => '<thead class="header">',
        'thead_close' => '</thead>',
        'tbody_open'  => '<tbody>',
        'tbody_close' => '</tbody>',
        'row_open'    => '<tr>',
        'row_close'   => '</tr>',
        'tfoot_open'  => '<tfoot>',
        'tfoot_close' => '</tfoot>',
    ];

    public $script = '';

    public $tableHeading = '';
    public $tableBody    = '';
    public $tableFooter  = '';

    public function __construct($datos, $campos)
    {
        $this->script = '<script type="text/javascript" src="'.asset('js/reporte.js').'"></script>';
        $this->datos = $datos;
        $this->campos = $campos;
    }

    /**
     * Formatea un valor de acuerdo a los parametros de un reporte
     *
     * @param  string $valor           Valor a formatear
     * @param  array  $arr_param_campo Parametros del campo
     * @param  array  $registro        Registro del valor, para extraer valores del link
     * @param  string $campo           [description]
     * @return string                  Variable formateada
     */
    public function formatoReporte($valor = '', $arr_param_campo = array(), $registro = array(), $campo = '')
    {
        $funcFormatoDetalle = function ($valor, $arr_param_campo, $registro, $campo) {
            $registro['permanencia'] = $campo;
            $arr_indices = ['id_tipo', 'centro', 'almacen', 'lote', 'estado_stock', 'material', 'tipo_material', 'permanencia'];
            $valor_desplegar = fmtCantidad($valor);

            return anchor(
                $arr_param_campo['href'].'?'.http_build_query(array_intersect_key($registro, array_flip($arr_indices))),
                ($valor_desplegar === '') ? ' ' : $valor_desplegar
            );
        };

        $arrFormatos = [
            'texto'         => function ($valor) {return $valor;},
            'fecha'         => function ($valor) {return fmt_fecha($valor);},
            'numero'        => function ($valor) {return fmt_cantidad($valor, 0, TRUE);},
            'valor'         => function ($valor) {return fmt_monto($valor, 'UN', '$', 0, TRUE);},
            'valor_pmp'     => function ($valor) {return fmt_monto($valor, 'UN', '$', 0, TRUE);},
            'numero_dif'    => function ($valor) {return fmt_cantidad($valor, 0, TRUE, TRUE);},
            'valor_dif'     => function ($valor) {return fmt_monto($valor, 'UN', '$', 0, TRUE, TRUE);},
            'link'          => function ($valor, $param) {return link_to($param['href'] . $valor, $valor);},
            'link_registro' => function ($valor, $param, $registro) {
                return link_to(
                    $param['href'].'/'.collect($param['href_registros'])
                        ->map(function ($elem) use($registro) {return $registro->{$elem};})
                        ->implode('/'),
                    $valor
                );
            },
            'link_detalle_series' => $funcFormatoDetalle,
        ];

        $tipo_dato = $arr_param_campo['tipo'];
        if (!array_key_exists($tipo_dato, $arrFormatos)) {
            return $valor;
        }

        return call_user_func_array($arrFormatos[$tipo_dato], array($valor, $arr_param_campo, $registro, $campo));
    }

    /**
     * Agrega elementos relacionados al ordenamiento al arreglo de campos
     *
     * @param  array  $campos       Arreglo de campos del reporte
     * @param  string $campoDefault Campo default en caso que no venga informado
     * @return void
     */
    public static function setOrderCampos(&$campos, $campoDefault = '')
    {
        $sort_by = empty(request('sort')) ? $campoDefault : request('sort');
        $sort_by = ( ! preg_match('/^[+\-](.*)$/', $sort_by)) ? '+'.$sort_by : $sort_by;

        $sort_by_field  = substr($sort_by, 1, strlen($sort_by));
        $sort_by_order  = substr($sort_by, 0, 1);
        $new_orden_tipo = ($sort_by_order === '+') ? '-' : '+';

        foreach ($campos as $campo => $valor) {
            if (!array_key_exists('titulo', $valor)) {
                $campos[$campo]['titulo'] = $campo;
            }
            if (!array_key_exists('class', $valor)) {
                $campos[$campo]['class'] = '';
            }
            if (!array_key_exists('tipo', $valor)) {
                $campos[$campo]['tipo'] = 'texto';
            }

            $campos[$campo]['sort'] = (($campo === $sort_by_field) ? $new_orden_tipo : '+').$campo;
            $order_icon = (substr($campos[$campo]['sort'], 0, 1) === '+') ? 'sort-amount-desc' : 'sort-amount-asc';
            $campos[$campo]['img_orden'] = ($campo === $sort_by_field) ? " <span class=\"fa fa-{$order_icon}\" ></span>" : '';
        }

    }

    /**
     * Devuelve string de ordenamiento
     * @param  string $sort_by Orden en formato: +campo1,+campo2,-campo3
     * @return string          Orden en formato: campo1 ASC, campo2 ASC, campo3 DESC
     */
    public function getOrderBy($sort_by)
    {
        return collect(explode(',', $sort_by))
            ->map(function ($value) {
                $value = ( ! preg_match('/^[+\-](.*)$/', trim($value))) ? '+'.trim($value) : trim($value);
                return substr($value, 1, strlen($value)).((substr($value, 0, 1) === '+') ? ' ASC' : ' DESC');
            })
            ->implode(', ');
    }

    /**
     * Imprime reporte
     *
     * @param  array $arr_campos Arreglo con los campos del reporte
     * @param  array $arr_datos  Arreglo con los datos del reporte
     * @return string            Reporte
     */
    public function make()
    {
        $datos = $this->datos;
        $campos = $this->campos;
        $template = $this->template;

        $subtotalAnt = '***init***';

        $camposTotalizables = array('numero', 'valor', 'numero_dif', 'valor_dif', 'link_detalle_series');
        $initTotalSubtotal = collect($this->campos)
            ->filter(function ($elem) use ($camposTotalizables) {
                return in_array($elem['tipo'], $camposTotalizables);
            })
            ->map(function ($elem) {
                return 0;
            });

        $arrTotales  = [
            'campos'   => $camposTotalizables,
            'total'    => $initTotalSubtotal->map(function ($elem, $llave) use ($datos) {
                return $datos->sum($llave);
            })->all(),
            'subtotal' => $initTotalSubtotal,
        ];

        // --- ENCABEZADO REPORTE ---
        $this->setHeading($this->reporteLineaEncabezado($this->campos, $arrTotales));

        // --- CUERPO REPORTE ---
        $numLinea = 0;
        $this->tableBody = $this->template['tbody_open']
            .$datos->reduce(function ($carry, $elem) use ($campos, &$numLinea, $template) {
                $numLinea += 1;
                return $carry
                    .$template['row_open']
                    .$this->tableRow($this->reporteLineaDatos($elem, $campos, $numLinea))
                    .$template['row_close'];
            }, '')
            .$this->template['tbody_close'];

        // --- TOTALES ---
        $this->setFooter($this->reporteLineaTotales('total', $campos, $arrTotales));

        return $template['table_open'].$this->tableHeading.$this->tableBody.$this->tableFooter.$template['table_close'].' '.$this->script;
    }


    // --------------------------------------------------------------------

    /**
     * Genera arreglo con datos de encabezado del reporte
     *
     * @param  array $arr_campos  Arreglo con la descripcion de los campos del reporte
     * @param  array $arr_totales Arreglo con los totales y subtotales de los campos del reporte
     * @return array              Arreglo con los campos del encabezado
     */
    private function reporteLineaEncabezado($arr_campos = array(), &$arr_totales = array())
    {
        return array_merge(
            array(''),
            collect($arr_campos)
                ->map(function($elem) {
                    return array(
                        'data' => "<span data-sort=\"{$elem['sort']}\" data-toggle=\"tooltip\" title=\"Ordenar por campo {$elem['titulo']}\">{$elem['titulo']}</span>{$elem['img_orden']}",
                        'class' => isset($elem['class']) ? $elem['class'] : '',
                    );
                })
                ->all()
        );
    }

    // --------------------------------------------------------------------

    /**
     * Genera arreglo con los datos de una linea del reporte
     *
     * @param  array   $linea    Arreglo con los valores de la linea
     * @param  array   $campos   Arreglo con la descripcion de los campos del reporte
     * @param  integer $numLinea Numero de linea actual
     * @return array             Arreglo con los campos de una linea
     */
    private function reporteLineaDatos($linea = [], $campos = array(), $numLinea = 0)
    {
        return (array_merge(
            array(array('data' => fmt_cantidad($numLinea), 'class' => 'text-muted')),
            collect($campos)->map(function ($elem, $llave) use ($linea) {
                return array(
                    'data' => $this->formatoReporte($linea->$llave, $elem, $linea, $llave),
                    'class' => isset($elem['class']) ? $elem['class'] : '',
                );
            })
            ->all()
        ));
    }

    // --------------------------------------------------------------------

    /**
     * Genera arreglo con los datos de una linea de total o subtotal del reporte
     *
     * @param  string $tipo            Tipo de linea a imprimir (total o subtotal)
     * @param  array  $arrCampos      Arreglo con la descripcion de los campos del reporte
     * @param  array  $arrTotales     Arreglo con los totales y subtotales de los campos del reporte
     * @param  string $nombre_subtotal Texto con el nombre del subtotal
     * @return array                   Arreglo con los campos de una linea de total o subtotal
     */
    private function reporteLineaTotales($tipo = '', $arrCampos = array(), $arrTotales = array(), $nombre_subtotal = '')
    {
        return (array_merge(
            array(''),
            collect($arrCampos)->map(function ($elem, $llave) use ($tipo, $arrTotales) {
                return array(
                    'data' => in_array($elem['tipo'], $arrTotales['campos'])
                        ? $this->formatoReporte($arrTotales[$tipo][$llave], $elem)
                        : '',
                    'class' => isset($elem['class']) ? $elem['class'] : '',
                );
            })
            ->all()
        ));
    }


    // --------------------------------------------------------------------

    /**
     * Recupera el nombre del campo con el tipo subtotal
     *
     * @param  array $arr_campos Arreglo con la descripcion de los campos del reporte
     * @return string             Nombre del campo con el tipo subtotal
     */
    private function _get_campo_subtotal($arr_campos = array())
    {
        foreach($arr_campos as $nombre_campo => $parametros_campo)
        {
            if ($parametros_campo['tipo'] === 'subtotal')
            {
                return $nombre_campo;
            }
        }
        return NULL;
    }

    // --------------------------------------------------------------------

    /**
     * Indica si la linea del reporte actual contiene un nuevo valor del campo subtotal
     *
     * @param  array  $arr_linea    Arreglo con los valores de la linea
     * @param  array  $arr_campos   Arreglo con la descripcion de los campos del reporte
     * @param  string $subtotal_ant Nombre del campo con el subtotal anterior
     * @return boolean              Indicador si la linea actual cambio el valor del campo subtotal
     */
    private function _es_nuevo_subtotal($arr_linea = array(), $arr_campos = array(), $subtotal_ant = NULL)
    {
        $campo_subtotal = $this->_get_campo_subtotal($arr_campos);

        if ($subtotal_ant === '***init***' OR $arr_linea[$campo_subtotal] !== $subtotal_ant)
        {
            return TRUE;
        }

        return FALSE;
    }


    // --------------------------------------------------------------------

    /**
     * Genera linas de totalización del subtotal e inicio de un nuevo grupo
     *
     * @param  array  $arr_linea    Arreglo con los valores de la linea
     * @param  array  $arr_campos   Arreglo con la descripcion de los campos del reporte
     * @param  array  $arr_totales  Arreglo con los totales y subtotales de los campos del reporte
     * @param  string $subtotal_ant Nombre del campo con el subtotal anterior
     * @return void
     */
    private function _reporte_linea_subtotal($arr_linea = array(), $arr_campos = array(), &$arr_totales = array(), &$subtotal_ant = NULL)
    {
        $ci =& get_instance();

        $campo_subtotal = $this->_get_campo_subtotal($arr_campos);

        // si el subtotal anterior no es nulo, se deben imprimir linea de subtotales
        if ($subtotal_ant !== '***init***')
        {
            $ci->table->add_row($this->_reporte_linea_totales('subtotal', $arr_campos, $arr_totales, $subtotal_ant));

            // agrega linea en blanco
            $ci->table->add_row(array_fill(0, count($arr_campos) + 1, ''));
        }

        // agrega linea con titulo del subtotal
        $ci->table->add_row(array(
            'data' => '<span class="fa fa-minus-circle"></span> <strong>'.$arr_linea[$campo_subtotal].'</strong>',
            'colspan' => count($arr_campos) + 1,
        ));

        // nuevo subtotal, y deja en cero, la suma de subtotales
        $subtotal_ant = $arr_linea[$campo_subtotal];

        foreach ($arr_campos as $nombre_campo => $arr_param_campo)
        {
            if (in_array($arr_param_campo['tipo'], $arr_totales['campos']))
            {
                $arr_totales['subtotal'][$nombre_campo] = 0;
            }
        }
    }


    public function tableRow($row = [], $rowDataElem = 'td')
    {
        return collect($row)->reduce(function ($carry, $elem) use ($rowDataElem) {
            $elem = is_array($elem) ? $elem : ['data' => $elem];
            return $carry
                .'<'.$rowDataElem.(array_key_exists('class', $elem) ? ' class="'.$elem['class'].'"' : '').'>'
                .$elem['data']
                .'</'.$rowDataElem.'>';
        }, '');
    }

    public function setHeading($heading = [])
    {
        $this->tableHeading = $this->template['thead_open'].$this->tableRow($heading, 'th').$this->template['thead_close'];
    }

    public function setFooter($footer = [])
    {
        $this->tableFooter = $this->template['tfoot_open'].$this->tableRow($footer, 'th').$this->template['tfoot_close'];
    }
}
