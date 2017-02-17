@extends('common.app_layout')

@section('modulo')
{{ Form::open() }}
{{ Form::hidden('tipo_op', $tipoOp) }}
<div class="accordion hidden-print">
	<div class="panel panel-default">

		<div class="panel-heading">
			<div class="row">
				<div class="col-md-8">
					<a href="#form_param" class="accordion-toggle" data-toggle="collapse">
						<span class="fa fa-filter"></span>
						{{ trans('stock.sap_panel_params') }}
					</a>
				</div>
			</div>
		</div>

		<div class="panel-collapse collapse in" id="form_param">
			<div class="panel-body">

                @include('orm.validation_errors')

				<div class="col-md-4 form-group {{ $errors->has('fecha[]') ? 'has-error' : '' }}">
					<label class="control-label">{{ trans('stock.sap_label_dates') }}</label>
					<div class="radio">
						<label>
							{{ Form::radio('sel_fechas', 'ultdia', request()->input('sel_fechas', 'ultdia') === 'ultdia') }}
							{{ trans('stock.sap_radio_date1') }}
						</label>
					</div>
					<div class="radio">
						<label>
							{{ Form::radio('sel_fechas', 'todas', request()->input('sel_fechas', 'ultdia') === 'todas') }}
							{{ trans('stock.sap_radio_date2') }}
						</label>
					</div>
					{{ Form::select('fecha[]', $comboFechas, request()->input('fecha'), ['multiple'=>'multiple', 'id'=>'select_fechas', 'size'=>10, 'class'=>'form-control']) }}
				</div>

				<div class="col-md-4 form-group {{ $errors->has('almacenes[]') ? 'has-error' : '' }}">
					<label class="control-label">{{ trans('stock.sap_label_alm') }}</label>
					<div class="radio">
						<label>
							{{ Form::radio('sel_tiposalm', 'sel_tiposalm', request()->input('sel_tiposalm', 'sel_tiposalm') === 'sel_tiposalm') }}
							{{ trans('stock.sap_radio_alm1') }}
						</label>
					</div>
					<div class="radio">
						<label>
							{{ Form::radio('sel_tiposalm', 'sel_almacenes', request()->input('sel_tiposalm', 'sel_tiposalm') === 'sel_almacenes') }}
							{{ trans('stock.sap_radio_alm2') }}
						</label>
					</div>
					{{ Form::select('almacenes[]', $comboAlmacenes, request()->input('almacenes'), ['multiple'=>'multiple', 'id'=>'select_almacenes', 'size'=>10, 'class'=>'form-control']) }}
					<div id="show_tiposalm">
						<div class="checkbox">
							<label>
								{{ Form::checkbox('almacen', 'almacen', request()->input('almacen')) }}
								{{ trans('stock.sap_check_show_alm') }}
							</label>
						</div>
					</div>
				</div>

				<div class="col-md-4">
					<div class="form-group">
						<label class="control-label">{{ trans('stock.sap_label_mats') }}</label>

                        <!--
						<div class="checkbox">
							{{-- Form::checkbox('tipo_articulo', 'tipo_articulo', set_checkbox('tipo_articulo', 'tipo_articulo')) --}}
							Desplegar detalle tipos articulo
						</div>
                        -->

						<div class="checkbox">
							<label>
								{{ Form::checkbox('material', 'material', request()->input('material')) }}
								{{ trans('stock.sap_check_mat') }}
							</label>
						</div>
						<div class="checkbox">
							<label>
								{{ Form::checkbox('lote', 'lote', request()->input('lote')) }}
								{{ trans('stock.sap_check_lotes') }}
							</label>
						</div>
						<div class="checkbox">
							<label>
								{{ Form::checkbox('tipo_stock', 'tipo_stock', request()->input('tipo_stock')) }}
								{{ trans('stock.sap_check_tipstock') }}
							</label>
						</div>

						<?php if ($tipoOp === 'MOVIL'): ?>
						<div>
							<div class="checkbox-inline">
								{{ Form::checkbox('tipo_stock_equipos', 'tipo_stock_equipos', request()->input('tipo_stock_equipos')) }}
								{{ trans('stock.sap_radio_equipos') }}
							</div>
							<div class="checkbox-inline">
								{{ Form::checkbox('tipo_stock_simcard', 'tipo_stock_simcard', request()->input('tipo_stock_simcard')) }}
								{{ trans('stock.sap_radio_sim') }}
							</div>
							<div class="checkbox-inline">
								{{ Form::checkbox('tipo_stock_otros', 'tipo_stock_otros', request()->input('tipo_stock_otros')) }}
								{{ trans('stock.sap_radio_otros') }}
							</div>
						</div>
						<?php endif; ?>
					</div>

					<hr/>
					<div class="pull-right">
						<button type="submit" name="submit" class="btn btn-primary">
							<span class="fa fa-search"></span>
							{{ trans('stock.sap_button_report') }}
						</button>
						<button type="submit" name="excel" value="excel" class="btn btn-default">
							<span class="fa fa-file-text-o"></span>
							{{ trans('stock.sap_button_export') }}
						</button>
					</div>

				</div>
			</div>
		</div>
	</div>

</div>

{{ Form::close() }}

{!! $tablaStock !!}


<script type="text/javascript">
$(document).ready(function () {

    $('input[name="sel_fechas"]').click(function (event) {
        tipo_fecha = $('input[name="sel_fechas"]:checked').val();
        tipo_op    = $('input[name="tipo_op"]').val();

        $('#select_fechas').html('');
        var url_datos = js_base_url + 'stock/consulta-fechas/' + tipo_op + '/' + tipo_fecha;
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
        var url_datos = js_base_url + 'stock/consulta-almacenes/' + tipo_op + '/' + tipo_alm;
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
});
</script>


<?php if ($datosGrafico): ?>
<div class="accordion">
    <div class="panel panel-default">
        <div class="panel-heading">
            <a href="#panel_graficos" class="accordion-toggle" data-toggle="collapse">
                {{ trans('stock.sap_panel_graph') }}
            </a>
        </div>
        <div class="panel-body collapse" id="panel_graficos">
            <div class="row">
                <div class="col-md-4">
                    <div>
                        <strong>{{ trans('stock.sap_label_mostrar_mat_}</stron') }}
                    </div>
                    <div>
                        <label class="checkbox-inline">
                            <?= form_radio('sel_graph_tipo', 'equipos', set_radio('sel_graph_tipo', 'equipos'), 'id="sel_graph_tipo_equipos"'); ?>
                            {{ trans('stock.sap_radio_equipos') }}
                        </label>
                        <label class="checkbox-inline">
                            <?= form_radio('sel_graph_tipo', 'simcard', set_radio('sel_graph_tipo', 'simcard'), 'id="sel_graph_tipo_simcard"'); ?>
                            {{ trans('stock.sap_radio_sim') }}
                        </label>
                        <label class="checkbox-inline">
                            <?= form_radio('sel_graph_tipo', 'otros', set_radio('sel_graph_tipo', 'otros'), 'id="sel_graph_tipo_otros"'); ?>
                            {{ trans('stock.sap_radio_otros') }}
                        </label>
                    </div>
                    <div>
                        <strong>Mostrar tipo de dato</strong>
                    </div>
                    <div>
                        <label class="checkbox-inline">
                            <?= form_radio('sel_graph_valor', 'cantidad', set_radio('sel_graph_valor','cantidad'), 'id="sel_graph_valor_cantidad"'); ?>
                            Cantidad
                        </label>
                        <label class="checkbox-inline">
                            <?= form_radio('sel_graph_valor', 'monto', set_radio('sel_graph_valor','monto'), 'id="sel_graph_valor_monto"'); ?>
                            Monto
                        </label>
                    </div>
                </div>
                <div class="col-md-8">
                    <div style="width:600px; margin-left:auto; margin-right:auto;">
                        <div id="chart" class="jqplot-target" style="width: 100%; height: 450px;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{base_url}js/jqplot/jqplot.barRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.categoryAxisRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.pointLabels.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.canvasTextRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/jqplot/jqplot.canvasAxisLabelRenderer.min.js"></script>
<script type="text/javascript" src="{base_url}js/view_stock_datos.js"></script>

<script language="javascript">
    var data_grafico = {
        q_equipos: <?= $datos_grafico['serie_q_equipos']; ?>,
        v_equipos: <?= $datos_grafico['serie_v_equipos']; ?>,
        q_simcard: <?= $datos_grafico['serie_q_simcard']; ?>,
        v_simcard: <?= $datos_grafico['serie_v_simcard']; ?>,
        q_otros: <?= $datos_grafico['serie_q_otros']; ?>,
        v_otros: <?= $datos_grafico['serie_v_otros']; ?>,
        x_label: <?= $datos_grafico['str_eje_x']; ?>,
        series_label: <?= $datos_grafico['str_label_series']; ?>,
    }

    $(document).ready(function(){
        $('input:radio[name=sel_graph_tipo], input:radio[name=sel_graph_valor]').click(function (event) {
            render_grafico(data_grafico, $('input:radio[name=sel_graph_tipo]:checked').val(), $('input:radio[name=sel_graph_valor]:checked').val());
        });

    });
</script>

<?php endif ?>
@endsection