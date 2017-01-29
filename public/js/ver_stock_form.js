/* global $ */

$(document).ready(function () {

    $('input[name="sel_fechas"]').click(function (event) {
        tipo_fecha = $('input[name="sel_fechas"]:checked').val();
        tipo_op    = $('input[name="tipo_op"]').val();

        $('#select_fechas').html('');
        var url_datos = js_base_url + 'stock_sap/ajax_fechas/' + tipo_op + '/' + tipo_fecha;
        $.get(url_datos, function (data) {$('#select_fechas').html(data); });
    });

    if ($('input[name="sel_tiposalm"]:checked').val() === 'sel_tiposalm') {
        $("#show_almacenes").hide();
    } else {
        $("#show_tiposalm").hide();
    }

    $('input[name="sel_tiposalm"]').click(function (event) {
        tipo_op  = $('input[name="tipo_op"]').val();
        tipo_alm = $('input[name="sel_tiposalm"]:checked').val();

        if (tipo_alm === 'sel_tiposalm') {
            $("#show_tiposalm").show();
        } else {
            $("#show_tiposalm").hide();
        }

        $('#select_almacenes').html('');
        var url_datos = js_base_url + 'stock_sap/ajax_almacenes/' + tipo_op + '/' + tipo_alm;
        $.get(url_datos, function (data) {$('#select_almacenes').html(data); });

    });

    $('div.mostrar-ocultar').click(function (event) {
        if ($('div.mostrar-ocultar span').html() === 'Ocultar') {
            $('div.mostrar-ocultar span').html('Mostrar');
        } else {
            $('div.mostrar-ocultar span').html('Ocultar');
        }
        $('div.mostrar-ocultar').parent().parent().next().toggle();
    });

    /**
    $('input[name="mostrar_cant_monto"]').click(function (event) {
        radio_selected = $('input[name="mostrar_cant_monto"]:checked');
        if (radio_selected.val() == 'cantidad') {
            $('table#stock td.text-right span').each(function() {$(this).text($(this).data('cantidad'))});
            $('table#stock th.text-right span').each(function() {$(this).text($(this).data('cantidad'))});
        } else {
            $('table#stock td.text-right span').each(function() {$(this).text($(this).data('monto'))});
            $('table#stock th.text-right span').each(function() {$(this).text($(this).data('monto'))});
        }
    });
    */

    function jTabla(idTabla) {
        var tabla = {headers: [], datos: [], campos_sumables: [], campos_montos: []};
        tabla.campos_sumables = ['LU', 'BQ', 'CC', 'TT', 'OT', 'total', 'EQUIPOS', 'SIMCARD', 'OTROS', 'cantidad', 'VAL LU', 'VAL BQ', 'VAL CC', 'VAL TT', 'VAL OT', 'monto', 'VAL EQUIPOS', 'VAL SIMCARD', 'VAL OTROS'];
        tabla.campos_montos   = ['VAL LU', 'VAL BQ', 'VAL CC', 'VAL TT', 'VAL OT', 'monto', 'VAL EQUIPOS', 'VAL SIMCARD', 'VAL OTROS'];
        var col_orden = -1, ord_orden = 1;

        Array.prototype.inicializa = function (val) {
            var len = this.length;
            while (--len >= 0) {
                this[len] = val;
            }
        };

        // Llena arreglo _headers
        var llena_arreglos = function () {
            $(idTabla + ' thead th').each(function (k, v) {
                tabla.headers[tabla.headers.length] = $.trim($(this).text());
            });

            // Llena arreglo _rows
            $(idTabla + ' tbody tr').each(function (row, v) {
                var arr_tmp = [];
                $(this).find('td').each(function (cell, v) {
                    arr_tmp[cell] = $.trim($(this).text());
                });

                tabla.datos[row] = arr_tmp;
            });
        };

        var agrega_orden_columnas = function () {
            $(idTabla + ' thead th').each(function () {
                var sCheckbox = '';
                if ($.inArray($(this).text(), tabla.campos_sumables) === -1) {
                    sCheckbox = '<input type="checkbox" name="sel_subtotal" value="' + $(this).text() + '"> ';
                }
                $(this).html(sCheckbox + '<span>' + $(this).text() + '</span>');
            });
            $(idTabla + ' thead th span').css('cursor', 'pointer');

        };

        var es_columna_sumable = function (ncol) {
            return (($.inArray(tabla.headers[ncol], tabla.campos_sumables) >= 0) ? true : false);
        };

        var es_columna_monto = function (ncol) {
            return (($.inArray(tabla.headers[ncol], tabla.campos_montos) >= 0) ? true : false);
        };

        var addCommas = function (nStr) {
            var x, x1, x2, rgx = /(\d+)(\d{3})/;
            nStr += '';
            x = nStr.split('.');
            x1 = x[0];
            x2 = (x.length > 1) ? ',' + x[1] : '';
            while (rgx.test(x1)) {
                x1 = x1.replace(rgx, '$1' + '.' + '$2');
            }
            return x1 + x2;
        };

        var genera_html_tabla = function () {
            var datos = tabla.datos;
            var a_html = [], h = -1;
            for (var i = 0; i < datos.length; i++)
            {
                a_html[++h] = '<tr>';
                for (var j = 0; j < datos[i].length; j++)
                {
                    a_html[++h] = '<td';
                    a_html[++h] = es_columna_sumable(j) ? ' class="ar"' : '';
                    a_html[++h] = '>';
                    a_html[++h] = datos[i][j];
                    a_html[++h] = '</td>';
                }
                a_html[++h] = '</tr>';
            }
            return a_html.join('');
        };

        var isNumeric = function (input) {
            var input2 = input.replace(/[\$\.]/g, '');
            return (input2 - 0) == input2 && input2.length > 0;
        };

        var sortMultidim = function (index, orden) {
            return function(a,b) {
                if (isNumeric(a[index]) && isNumeric(b[index])) {
                    return orden * (Number(a[index].toString().replace(/[\$\.]/g, '')) - Number(b[index].toString().replace(/[\$\.]/g, '')));
                } else {
                    return orden * ((a[index] < b[index]) ? -1 : ((a[index] > b[index]) ? 1 : 0));
                }
            };
        };

        var genera_linea_subtotales = function (arreglo_totales, columna_subtotal, texto_columna_subtotal) {
            var sSubtotal = '';
            for(var i = 0; i < tabla.headers.length; i++)
            {
                if (i == columna_subtotal) {
                    sSubtotal += '<td>Subtotal ' + texto_columna_subtotal + '</td>';
                } else if (es_columna_sumable(i)) {
                    sSubtotal += '<td class="ar">';
                    sSubtotal += es_columna_monto(i) ? ' $ ' : '';
                    sSubtotal += addCommas(arreglo_totales[i]) + '</td>';
                } else {
                    sSubtotal += '<td></td>';
                }
            }
            return sSubtotal;
        };

        this.activa_subtotales_columnas = function () {
            $(idTabla + ' thead th input[name="sel_subtotal"]').click(function(event) {
                var num_col_subtotal = $.inArray($(this).next().text(),tabla.headers);

                $(idTabla + ' tbody tr.subtotal').detach();

                // si la columna ya estaba seleccionada, saca la marca del checkbox
                if (!($(idTabla + ' thead th input[name="sel_subtotal"]:eq(' + num_col_subtotal +')').attr('checked'))) {
                    $(idTabla + ' thead th input[name="sel_subtotal"]').attr('checked', false);
                } else {
                    $(idTabla + ' thead th input[name="sel_subtotal"]').attr('checked', false);
                    $(idTabla + ' thead th input[name="sel_subtotal"]:eq(' + num_col_subtotal +')').attr('checked', true);

                    var clave_ant = '';
                    var arr_totales = new Array(tabla.headers.length);
                    arr_totales.inicializa(0);

                    $(idTabla + ' tbody tr').each(function() {
                        var arr_columnas = $(this).find('td').toArray();
                        if ($(arr_columnas[num_col_subtotal]).text() !== clave_ant && clave_ant !== '') {
                            $(this).before('<tr class="subtotal">' + genera_linea_subtotales(arr_totales, num_col_subtotal, clave_ant) + '</tr>');
                            arr_totales.inicializa(0);
                        }
                        clave_ant = $(arr_columnas[num_col_subtotal]).text();
                        for(var i=0; i<arr_columnas.length; i++)
                        {
                            if (es_columna_sumable(i)) {
                                arr_totales[i] += $(arr_columnas[i]).text().replace(/[\$\.]/g, '')*1;
                            }
                        }
                    });

                    // escribe ultima linea de subtotales
                    $(idTabla + ' tbody tr:last').after('<tr class="subtotal">' + genera_linea_subtotales(arr_totales, num_col_subtotal, clave_ant) + '</tr>');
                }
            });
        };

        this.activa_orden_columnas = function() {
            $(idTabla + ' thead th span').click(function(event) {
                var sort_index = $(idTabla + ' thead th span').index(this);

                if (sort_index == col_orden) {
                    ord_orden = -ord_orden;
                } else {
                    col_orden = sort_index;
                    ord_orden = 1;
                }

                tabla.datos.sort(sortMultidim(col_orden, ord_orden));
                $(idTabla + ' tbody')[0].innerHTML = genera_html_tabla();
            });
        };


        llena_arreglos();
        agrega_orden_columnas();
    }

    // var tablaDatos = new jTabla('table.reporte');
    // tablaDatos.activa_subtotales_columnas();
    // tablaDatos.activa_orden_columnas();

});